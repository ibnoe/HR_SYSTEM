<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Bus\SelfHandling;
use App\APIDTO\APIResponse as APIResponse;
use Hash, Auth, Exception;
use Illuminate\Support\MessageBag;

class Counting extends Command implements SelfHandling {

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct($model, $query, $sortby, $page = 1, $per_page = 10)
	{
		$this->errors = new MessageBag();

		$this->validate_sortable($model);
		$this->validate_searchable($model);
		$this->max_per_page = 100;

		// searchable
		$this->searchable = $model->searchable;

		// sortable
		$this->sortable = $model->sortable;

		// model
		$this->model = $model;
		$this->validate_model($model);

		// query
		$this->query = $query;
		$this->validate_query($query);

		// sort
		$this->sortby = $sortby;
		$this->validate_sortby($sortby);

		// page
		$this->page = $page;
		$this->validate_page($page);

		// per page
		$this->per_page = $per_page;
		$this->validate_perpage($per_page);

	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle()
	{		
		if($this->errors->count())
		{
			$response = new APIResponse((array)$this->model, $this->errors->toArray(), ['page' => $this->page, 'per_page' => $this->per_page]);
			return $response->toJson();
		}

		return $this->count();
	}

	protected function count()
	{
		$response = new APIResponse(['total' => $this->build_query()->count()], null, 1);
		return $response->toJson();
	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	private function build_query()
	{
		$model = $this->model;
		
		// generate query 
		if (!empty($this->query))
		{
			foreach ($this->query as $field => $value)
			{
				$model = call_user_func([$model,$this->searchable[strtolower($field)]], $value);
			}
		}

		return $model;
	}

	private function validate_query($query)
	{
		// format of query
		if (!is_array($query))
		{
			throw new Exception("Query: Must be an array ([field => value])", 1);
		}

		// query must be in searchable
		foreach ($query as $field => $value)
		{
			if (!array_key_exists(strtolower($field), ($this->searchable)))
			{
				$this->errors->add(1, $field . ": Is not searchable");
				$this->errors->add(1, 'Searchable Function');
				foreach ($this->model->searchableScope as $key => $value) 
				{
					$this->errors->add(2, $key. ' => '. $value);
				}
			}
		}
	}

	/**
	 * validate sortby
	 *
	 * @return void
	 * @author 
	 **/
	private function validate_sortby($sortby)
	{
		// format of sortby
		if (!is_array($sortby))
		{
			throw new Exception("SortBy: Must be an array ([field => asc|desc])", 3);
		}

		// sortby must be in sortable
		foreach ($sortby as $field => $value)
		{
			if (!in_array(strtolower($field), $this->sortable))
			{
				$this->errors->add(4, $field . ": Is not sortable");
				$this->errors->add(4, 'Sortable Function');
				foreach ($this->model->sortable as $key => $value) 
				{
					$this->errors->add(4, $value);
				}
			}

			if (!str_is('asc', $value) && !str_is('desc', $value))
			{
				$this->errors->add(9, 'Sort mode must be either asc or desc');
			}
		}
	}

	/**
	 * validate page
	 *
	 * @return void
	 * @author 
	 **/
	private function validate_page($page)
	{
		if (!is_integer($page) || $page < 1)
		{
			$this->errors->add(5, "Page must be an integer greater than 0");
		}
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	private function validate_perpage($per_page)
	{
		if (!is_integer($per_page) || $per_page < 1 || $per_page > 100)
		{
			$this->errors->add(6, "Page must be an integer between 1 and 100");
		}
	}

	private function validate_searchable($model)
	{
		if (!isset($model->searchable))
		{
			throw new Exception("Searchable field has not been set", 10);
		}

		// check if function exists
		foreach ($model->searchable as $field => $func)
		{
			if (!method_exists($model, 'scope'.$func))
			{
				$this->errors->add(11, "Function for searching $field ($func) does not exist");
			}
		}
	}

	private function validate_sortable($model)
	{
		if (!isset($model->sortable))
		{
			$this->errors->add(12, "Sortable field has not been set");
		}
	}

	private function validate_model()
	{
		if (!isset($this->model))
		{
			$this->errors->add(13, "Model does not exist");
		}
	}
}
