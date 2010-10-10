<?php
/**
 * To define the schema of a model
 * 
 * @todo There is a lot more to do with relationships.  As of this (03-06-10), you can do very 
 * simple hasOne and hasMany.  We need to deal with the rest
 * 
 * @package db
 * @author Justin Palmer
 **/
class Schema
{
	/**
	 * The current model
	 *
	 * @author Justin Palmer
	 * @var Model
	 */
	private $model;
	/**
	 * The rules for the current model.
	 *
	 * @author Justin Palmer
	 * @var Hash
	 */
	public $rules;
	/**
	 * The relationships defined
	 *
	 * @author Justin Palmer
	 * @var hash
	 */
	public $relationships;
	/**
	 * The last relationship
	 *
	 * @author Justin Palmer
	 * @var Hash
	 */
	private $last_relationship = null;
	/**
	 * The last column that a rule was added to.
	 *
	 * @var string
	 */
	private $last_rule_column = null;
	/**
	 * The required elements
	 * 
	 * @var array
	 */
	public $required = array();
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
		//Make sure the property exists.
		$this->model->hasProperty($column);
		if($rule instanceof RequiredRule){
			$this->required[] = $column;
		}
		//Get the rules for this property.
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
		return $this->rules;
	}
	/**
	 * Add the required rule to all of the properties listed
	 *
	 * @params mixed
	 * @return void
	 * @author Justin Palmer
	 **/
	public function required($args)
	{
		$args = func_get_args();
		foreach($args as $property){
			$this->required[] = $property;
			$this->rule($property, new RequiredRule());
		}
	}
	/**
	 * Set the table name for the current relationship explicitly.
	 *
	 * @return Schema
	 * @author Justin Palmer
	 **/
	public function className($table)
	{
		return $this->prop(Inflections::underscore($table) . '_id')->addOption(array('table' => Inflections::tableize($table)), 'table');
	}
	/**
	 * Set the property to access when doing the where
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function prop($prop)
	{
		return $this->addOption(array('prop' => $prop), 'prop');
	}
	/**
	 * The alias that the last relationship should be, this will be used in the join query.
	 *
	 * @return Schema
	 * @author Justin Palmer
	 **/
	public function alias($alias)
	{
		return $this->addOption(array('alias'=>$alias), 'alias');
	}
	/**
	 * How the join should be preformed(base.id = alias_table.id).
	 *
	 * @return Schema
	 * @author Justin Palmer
	 **/
	public function on($on)
	{
		return $this->addOption(array('on'=>$on), 'on');
	}
	/**
	 * Set the foreign key to join on if it does not follow standard.
	 *
	 * @return Schema
	 * @author Justin Palmer
	 **/
	public function foreignKey($key)
	{
		return $this->addOption(array('foreign_key' => $key), 'foreignKey');
	}
	/**
	 * belongs to
	 *
	 * @return Schema
	 * @author Justin Palmer
	 **/
	public function belongsTo($name)
	{	
		$this->last_relationship = strtolower($name);
		return $this->prop($name)->addRelationship($name, 'belongs-to');
	}
	/**
	 * has many
	 *
	 * @return Schema
	 * @author Justin Palmer
	 **/
	public function hasMany($name)
	{	
		$this->last_relationship = strtolower($name);
		return $this->addRelationship($name, 'has-many');
	}
	/**
	 * has one
	 *
	 * @return Schema
	 * @author Justin Palmer
	 **/
	public function hasOne($name)
	{	
		$this->last_relationship = strtolower($name);
		return $this->addRelationship($name, 'has-one');	
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
		if($name != 'on'){
			//Regenerate the on to see if there is anything that needs changed.
			$options->on = $this->autoGenerateOn(strtolower($options->name));
		}
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
		$options->foreign_key = Inflections::foreignKey($this->model->table_name());
		$this->relationships->set(strtolower($name), $options);
		$options->on = $this->autoGenerateOn(strtolower($name));
		$this->relationships->set(strtolower($name), $options);
		$this->prop(Inflections::underscore($name) . '_id');
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
				//$ret = $this->model->alias() . "." . $this->model->primary_key() . " = " . $options->alias . "." . $options->foreign_key;
				$ret = $options->table . '.' . $this->model->primary_key() . ' = ?';
				break;
			case 'has-many':
				$ret = $options->table . "." . $options->foreign_key . " = ?";
				break;
		}
		return $ret;
	}
} // END class Schema