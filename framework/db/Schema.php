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
	public $rules;
	public $relationships;
	private $last_relationship = null;
	/**
	 * Constructor
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function __construct($model)
	{
		$this->model = $model;
		$this->rules = new Hash();
		$this->relationships = new Hash();
	}
	/**
	 * Add a rule to a column
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function rule($column, Rule $rule)
	{
		if(!$this->model->columns()->isKey($column))
			throw new NoColumnInTableException($column, $this->model->table_name());
		$rules = $this->rules->get($column);
		if($rules === null){
			$rules = array();
		}
		$rules[] = $rule;
		$this->rules->set($column, $rules);				
	}
	/**
	 * Get the rules
	 *
	 * @return array
	 * @author Justin Palmer
	 **/
	public function rules()
	{
		return $this->rules->export();
	}
	/**
	 * Set the table name for the current relationship explicitly.
	 *
	 * @return Schema
	 * @author Justin Palmer
	 **/
	public function class_name($table)
	{
		$this->addOption(array('table', Inflections::tableize($table)), 'table');
	}
	/**
	 * The alias that the last relationship should be, this will be used in the join query.
	 *
	 * @return Schema
	 * @author Justin Palmer
	 **/
	public function alias($alias)
	{
		$this->addOption(array('alias'=>$alias), 'alias');
	}
	/**
	 * How the join should be preformed(base.id = alias_table.id).
	 *
	 * @return Schema
	 * @author Justin Palmer
	 **/
	public function on($on)
	{
		$this->addOption(array('on'=>$on), 'on');
	}
	/**
	 * belongs to
	 *
	 * @return Schema
	 * @author Justin Palmer
	 **/
	public function belongsTo($name)
	{	
		$this->last_relationship = $name;
		$this->addRelationship($name, 'belongs-to');
	}
	/**
	 * has many
	 *
	 * @return Schema
	 * @author Justin Palmer
	 **/
	public function hasMany($name)
	{	
		$this->last_relationship = $name;
		$this->addRelationship($name, 'has-many');
	}
	/**
	 * has one
	 *
	 * @return Schema
	 * @author Justin Palmer
	 **/
	public function hasOne($name)
	{	
		$this->last_relationship = $name;
		$this->addRelationship($name, 'has-one');
	}
	/**
	 * Add the option to the last relationship.
	 *
	 * @return Schema
	 * @author Justin Palmer
	 **/
	private function addOption(array $option, $name)
	{
		$value = current($option);
		$key = key($option);
		$options = $this->relationships->get($this->last_relationship);
		if($options === null)
			throw new NoSchemaRelationshipException($name);
		$options->$key = $value;
		$this->relationships->set($this->last_relationship, $options);
		return $this;
	}
	/**
	 * Add the relationship
	 *
	 * @return Schema
	 * @author Justin Palmer
	 **/
	private function addRelationship($name, $type)
	{
		$options = new stdClass;
		$options->name = $name;
		$options->type = $type;
		$options->alias = Inflections::underscore(str_replace('-', '_', $name));
		$options->table = Inflections::tableize($options->alias);
		$this->relationships->set($name, $options);
		$options->on = $this->autoGenerateOn($name);
		$this->relationships->set($name, $options);		
		return $this;
	}
	/**
	 * AutoGenerate the on for the specified relationship.
	 *
	 * @return string
	 * @author Justin Palmer
	 **/
	private function autoGenerateOn($name)
	{
		$options = $this->relationships->get($name);
		$ret = '';
		switch($options->type){
			case 'has-one':
				$ret = $this->model->alias() . "." . $this->model->primary_key() . " = " . $options->alias . "." . Inflections::foreignKey(Inflections::singularize($this->model->table_name()));
		}
		return $ret;
	}
} // END class Schema