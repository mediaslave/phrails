<?php
/**
 * These first two query partials are the same.
 */


	//$Schema->belongsTo('table_name')->as('bob');          
	//$Schema->belongsTo('table_name')->as('bob')->on('base.id = table_name_id');    
    
	//$Schema->hasMany('table_name')->as('fred');
	//$Schema->hasMany('table_name')->as('fred')->on('table_name_id');

	//$Schema->hasOne('table_name')->as('bob');
	//$Schema->hasOne('table_name')->as('bob')->on('table_name_id');

/**
 * To define the schema of a model
 *
 * @package db
 * @author Justin Palmer
 **/
class Schema
{
	private $model;
	/**
	 * Constructor
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function __construct($model)
	{
		$this->model = $model;
	}
} // END class Schema