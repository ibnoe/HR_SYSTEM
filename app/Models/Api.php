<?php namespace App\Models;


/* ----------------------------------------------------------------------
 * Document Model:
 * 	ID 								: Auto Increment, Integer, PK
 * 	branch_id 						: Required, Integer, FK from Branch
 * 	client 			 				: Required, unique, max : 255
 * 	secret 			 				: Required, max : 255
 * 	macadress 			 			: Required, max : 255
 * 	pc_name 			 			: Required, max : 255
 * 	tr_version 			 			: Required, max : 255
 *	created_at						: Timestamp
 * 	updated_at						: Timestamp
 * 	deleted_at						: Timestamp
 * ---------------------------------------------------------------------- */

/* ----------------------------------------------------------------------
 * Document Relationship :
	//other package
	1 Relationship belongsTo
	{
		Branch
	}

 * ---------------------------------------------------------------------- */

use Str, Validator, DateTime, Exception;

class Api extends BaseModel {

	use \App\Models\Traits\BelongsTo\HasBranchTrait;

	public 		$timestamps 		= true;

	protected 	$table 				= 	'apis';

	protected 	$fillable			= 	[
											'client' 							,
											'secret' 							,
											'macaddress' 						,
											'pc_name' 							,
											'tr_version' 						,
										];

	protected 	$rules				= 	[
											'client' 							=> 'required',
											'secret' 							=> 'required',
											'macaddress' 						=> 'required',
											'pc_name' 							=> 'required',
											'tr_version' 						=> '',
										];

	public $searchable 				= 	[
											'id' 								=> 'ID', 
											'branchid' 							=> 'BranchID', 
											
											'client' 							=> 'Client', 
											'secret' 							=> 'Secret', 
											'macaddress' 						=> 'MacAddress', 
											'withattributes' 					=> 'WithAttributes',
										];

	public $searchableScope 		= 	[
											'id' 								=> 'Could be array or integer', 
											'branchid' 							=> 'Could be array or integer', 
											
											'client' 							=> 'Must be string', 
											'secret' 							=> 'Must be string', 
											'macadress' 						=> 'Must be string', 
											'withattributes' 					=> 'Must be array of relationship',
										];

	public $sortable 				= 	['branch_id', 'created_at', 'client'];

	/* ---------------------------------------------------------------------------- CONSTRUCT ----------------------------------------------------------------------------*/
	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/
	static function boot()
	{
		parent::boot();

		Static::saving(function($data)
		{
			$validator = Validator::make($data->toArray(), $data->rules);

			if ($validator->passes())
			{
				return true;
			}
			else
			{
				$data->errors = $validator->errors();
				return false;
			}
		});
	}

	/* ---------------------------------------------------------------------------- ERRORS ----------------------------------------------------------------------------*/
	/**
	 * return errors
	 *
	 * @return MessageBag
	 * @author 
	 **/
	function getError()
	{
		return $this->errors;
	}

	/* ---------------------------------------------------------------------------- QUERY BUILDER ---------------------------------------------------------------------------*/
	
	/* ---------------------------------------------------------------------------- MUTATOR ---------------------------------------------------------------------------------*/

	/* ---------------------------------------------------------------------------- ACCESSOR --------------------------------------------------------------------------------*/
	
	/* ---------------------------------------------------------------------------- FUNCTIONS -------------------------------------------------------------------------------*/
	
	/* ---------------------------------------------------------------------------- SCOPE -------------------------------------------------------------------------------*/

	public function scopeID($query, $variable)
	{
		if(is_array($variable))
		{
			return $query->whereIn('apis.id', $variable);
		}
		return $query->where('apis.id', $variable);
	}
	
	public function scopeMacAddress($query, $variable)
	{
		if(is_array($variable))
		{
			return $query->whereIn('apis.macaddress', $variable);
		}
		return $query->where('apis.macaddress', $variable);
	}

	public function scopeClient($query, $variable)
	{
		return $query->where('client', $variable);
	}

	public function scopeSecret($query, $variable)
	{
		return $query->where('secret', $variable);
	}
}
