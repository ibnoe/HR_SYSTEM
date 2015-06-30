<?php namespace App\Http\Controllers\Organisation\Report\Attendance;

use Input, Session, App, Paginator, Redirect, DB, Config, Validator, Image;
use App\Http\Controllers\BaseController;
use Illuminate\Support\MessageBag;
use App\Console\Commands\Saving;
use App\Console\Commands\Getting;
use App\Console\Commands\Deleting;
use App\Console\Commands\Checking;
use App\Models\Organisation;
use App\Models\Person;
use App\Models\ProcessLog;

class PersonController extends BaseController 
{
	protected $controller_name 						= 'attendance';

	public function index()
	{		
		if(Input::has('org_id'))
		{
			$org_id 								= Input::get('org_id');
		}
		else
		{
			$org_id 								= Session::get('user.organisation');
		}

		if(Input::has('person_id'))
		{
			$person_id 								= Input::get('person_id');
		}
		else
		{
			App::abort(404);
		}

		if(Input::has('start'))
		{
			$start 									= date('Y-m-d', strtotime(Input::get('start')));
		}
		else
		{
			$start 									= date('Y-m-d', strtotime('first day of this month'));
		}

		if(Input::has('end'))
		{
			$end 									= date('Y-m-d', strtotime(Input::get('end')));
		}
		else
		{
			$end 									= date('Y-m-d', strtotime('last day of next month'));
		}

		if(!in_array($org_id, Session::get('user.organisationids')))
		{
			App::abort(404);
		}

		$search['id']								= $person_id;
		$search['organisationid']					= $org_id;
		$search['withattributes']					= ['organisation'];
		$sort 										= ['name' => 'asc'];
		if(Session::get('user.menuid')==4)
		{
			$search['chartchild'] 					= Session::get('user.chartpath');
		}
		$results 									= $this->dispatch(new Getting(new Person, $search, $sort , 1, 1));
		$contents 									= json_decode($results);		

		if(!$contents->meta->success)
		{
			App::abort(404);
		}

		$person 									= json_decode(json_encode($contents->data), true);
		$data 										= $person['organisation'];

		$this->layout->page 						= view('pages.organisation.report.attendance.person.index', compact('data', 'start', 'end', 'person'));

		return $this->layout;
	}

	public function create($id = null)
	{
		if(Input::has('org_id'))
		{
			$org_id 							= Input::get('org_id');
		}
		else
		{
			$org_id 							= Session::get('user.organisation');
		}

		if(!in_array($org_id, Session::get('user.organisationids')))
		{
			App::abort(404);
		}

		if(Input::has('person_id'))
		{
			$person_id 							= Input::get('person_id');
		}
		else
		{
			App::abort(404);
		}

		$search['id']							= $person_id;
		$search['organisationid']				= $org_id;
		$search['withattributes']				= ['organisation'];
		$sort 									= ['name' => 'asc'];
		$results 								= $this->dispatch(new Getting(new Person, $search, $sort , 1, 1));
		$contents 								= json_decode($results);		

		if(!$contents->meta->success)
		{
			App::abort(404);
		}

		$person 								= json_decode(json_encode($contents->data), true);
		$data 									= $person['organisation'];

		$this->layout->page 					= view('pages.organisation.report.attendance.person.create', compact('id', 'data', 'person'));

		return $this->layout;
	}

	public function store($id = null)
	{
		if(Input::has('id'))
		{
			$id 								= Input::get('id');
		}

		if(Input::has('org_id'))
		{
			$org_id 							= Input::get('org_id');
		}
		else
		{
			$org_id 							= Session::get('user.organisation');
		}

		if(!in_array($org_id, Session::get('user.organisationids')))
		{
			App::abort(404);
		}

		if(Input::has('person_id'))
		{
			$person_id 							= Input::get('person_id');
		}
		else
		{
			App::abort(404);
		}

		$search['id'] 							= $person_id;
		$search['organisationid'] 				= $org_id;
		$sort 									= ['name' => 'asc'];
		if(Session::get('user.menuid')==4)
		{
			$search['chartchild'] 				= Session::get('user.chartpath');
		}
		$results 								= $this->dispatch(new Getting(new Person, $search, $sort , 1, 1));
		$contents 								= json_decode($results);

		if(!$contents->meta->success)
		{
			App::abort(404);
		}

		$attributes 							= Input::only('modified_status');
		$attributes['modified_by']				= Session::get('loggedUser');
		$attributes['modified_at']				= date('Y-m-d H:i:s');

		$errors 								= new MessageBag();

		DB::beginTransaction();
		
		$content 								= $this->dispatch(new Saving(new ProcessLog, $attributes, $id, new Person, $person_id));
		$is_success 							= json_decode($content);
		
		if(!$is_success->meta->success)
		{
			foreach ($is_success->meta->errors as $key => $value) 
			{
				if(is_array($value))
				{
					foreach ($value as $key2 => $value2) 
					{
						$errors->add('ProcessLog', $value2);
					}
				}
				else
				{
					$errors->add('ProcessLog', $value);
				}
			}
		}

		if(!$errors->count())
		{
			DB::commit();
			return Redirect::route('hr.attendance.persons.index', ['person_id' => $person_id, 'org_id' => $org_id])->with('alert_success', 'Karir karyawan "' . $contents->data->name. '" sudah disimpan');
		}
		
		DB::rollback();
		return Redirect::back()->withErrors($errors)->withInput();
	}

	public function edit($id)
	{
		return $this->create($id);
	}

	public function show()
	{
		if(Input::has('org_id'))
		{
			$org_id 								= Input::get('org_id');
		}
		else
		{
			$org_id 								= Session::get('user.organisation');
		}

		if(Input::has('person_id'))
		{
			$person_id 								= Input::get('person_id');
		}
		else
		{
			App::abort(404);
		}

		if(Input::has('ondate'))
		{
			$ondate 								= date('Y-m-d', strtotime(Input::get('ondate')));
		}
		else
		{
			$ondate 								= date('Y-m-d', strtotime('now'));
		}

		if(!in_array($org_id, Session::get('user.organisationids')))
		{
			App::abort(404);
		}

		if(Input::has('start'))
		{
			$start 									= date('Y-m-d', strtotime(Input::get('start')));
		}
		else
		{
			$start 									= date('Y-m-d', strtotime('first day of this month'));
		}

		if(Input::has('end'))
		{
			$end 									= date('Y-m-d', strtotime(Input::get('end')));
		}
		else
		{
			$end 									= date('Y-m-d', strtotime('last day of next month'));
		}

		if(Session::get('user.menuid')==4)
		{
			$search['chartchild'] 					= Session::get('user.chartpath');
		}

		$search['id']								= $person_id;
		$search['organisationid']					= $org_id;
		$search['withattributes']					= ['organisation'];
		$sort 										= ['name' => 'asc'];
		if(Session::get('user.menuid')==4)
		{
			$search['chartchild'] 					= Session::get('user.chartpath');
		}
		$results 									= $this->dispatch(new Getting(new Person, $search, $sort , 1, 1));
		$contents 									= json_decode($results);		

		if(!$contents->meta->success)
		{
			App::abort(404);
		}

		$person 									= json_decode(json_encode($contents->data), true);
		$data 										= $person['organisation'];

		$this->layout->page 						= view('pages.organisation.report.attendance.person.log.index', compact('data', 'ondate', 'person', 'start', 'end'));

		return $this->layout;
	}

}