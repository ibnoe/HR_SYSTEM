<?php namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\MessageBag;
use App\Console\Commands\Getting;
use App\Models\PersonDocument;
use Input, Validator, App;

class PersonDocumentComposer extends WidgetComposer 
{
	protected function setRules()
	{
		$this->widget_rules['form_url']			= ['required', 'url'];						// url for form submit
		$this->widget_rules['search'] 			= ['array'];								// search: label for search
		$this->widget_rules['sort'] 			= ['array'];								// sort: label for sort
		$this->widget_rules['page'] 			= ['required', 'numeric'];					// page: label for page
		$this->widget_rules['per_page'] 		= ['required', 'numeric', 'max:100'];		// per page: label for per page
		$this->widget_rules['identifier'] 		= ['required', 'numeric'];					// identifier
	}

	protected function setData()
	{
		$results 								=  $this->dispatch(new Getting(new PersonDocument, $this->widget_data['search'], $this->widget_data['sort'] , $this->widget_data['page'], $this->widget_data['per_page']));

		$contents 								= json_decode($results);

		if(!$contents->meta->success)
		{
			foreach ($contents->meta->errors as $key => $value) 
			{
				if(is_array($value))
				{
					foreach ($value as $key2 => $value2) 
					{
						$this->widget_errors->add('PersonDocument', $value2);
					}
				}
				else
				{
					$this->widget_errors->add('PersonDocument', $value);
				}
			}

			$this->widget_data['persondocument-'.$this->widget_data['identifier']] 			= null;
		}
		else
		{
			$this->widget_data['persondocument-'.$this->widget_data['identifier']] 			= json_decode(json_encode($contents->data), true);
		}
		
	}
}