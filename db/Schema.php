<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
 * @package db
 */
/**
 * To define the schema of a model
 *
 * @todo Building SQL in schema is bad.  Needs to be done by the adapter.
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
	public function rule($column, $rule)
	{
		$args = func_get_args();
		$rule = array_pop($args);
		if(!($rule instanceof Rule)) {
			throw new Exception('Schema::rule expects the last parameter to be a Rule in ' . get_class($this->model));
		}

		foreach($args as $column) {
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
	public function className($table, $is_global_class=false)
	{
    $namespace = PR_APPLICATION_NAMESPACE . '\App\Models\\';
		if($is_global_class){
      $pieces = explode('\\', $table);
      $table = array_pop($pieces);
      $namespace = implode('\\', $pieces) . '\\';
    }
		return $this->prop(Inflections::underscore($table) . '_id')
								->addOption(array('table' => Inflections::tableize($table)), 'table')
								->addOption(array('klass' => $namespace . $table), 'klass');
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
	 * Set the table name
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function table($table_name)
	{
		return $this->addOption(array('table' => $table_name), 'table');
	}
	/**
	 * Where claus no dynamic
	 *
	 * @return string $clause
	 * @author Justin Palmer
	 **/
	public function where($clause, $operand='AND')
	{
		$options = $this->relationships->get($this->last_relationship);
		$options->where .= $clause . ' ' . $operand . ' ';
		$this->relationships->set($this->last_relationship, $options);
		return $this;
	}

	/**
	 * Set the order by for a relationship
	 *
	 * @return string $order
	 * @author Justin Palmer
	 **/
	public function order($order)
	{
		$options = $this->relationships->get($this->last_relationship);
		$options->order_by = $order;
		$this->relationships->set($this->last_relationship, $options);
		return $this;
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
	 * Indicate a relationship is through another class.
	 *
	 * @return Schema
	 * @author Justin Palmer
	 **/
	public function thru($klass, $is_global_class=false)
	{
		$namespace = '';
		if(!$is_global_class){
			$namespace = PR_APPLICATION_NAMESPACE . '\App\Models\\';
		}
		return $this->addOption(array('thru'=>$namespace . $klass), 'thru');
	}
	public function through($klass, $is_global_class=false){return $this->thru($klass, $is_global_class);}
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
	public function hasOne($name)
	{
		$this->last_relationship = strtolower($name);
		return $this->addRelationship($name, 'has-one')->className(Inflections::singularize($name));
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
		return $this->addRelationship($name, 'has-many')->className(Inflections::singularize($name));
	}
	/**
	 * has one
	 *
	 * @return Schema
	 * @author Justin Palmer
	 **/
	public function belongsTo($name)
	{
		$this->last_relationship = strtolower($name);
		return $this->addRelationship($name, 'belongs-to')->className(Inflections::singularize($name));
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

		if($name == 'prop' && ($options->type == 'has-many' || $options->type == 'has-one')){
			$value = $this->model->primary_key();
		}
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
		$options->klass = PR_APPLICATION_NAMESPACE . '\App\Models\\' . $name;
		$options->alias = Inflections::underscore(str_replace('-', '_', $name));
		$options->table = Inflections::tableize($options->alias);
		$options->foreign_key = Inflections::foreignKey($this->model->table_name());
		$options->where = '';
		$options->order_by = '';
		$options->thru = '';
		$options->join = '';
		$this->relationships->set(strtolower($name), $options);
		$options->on = $this->autoGenerateOn(strtolower($name));
		$this->relationships->set(strtolower($name), $options);
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
			case ($options->type =='belongs-to' ):
				//$ret = $this->model->alias() . "." . $this->model->primary_key() . " = " . $options->alias . "." . $options->foreign_key;
				$ret = $options->alias . '.' . $this->model->primary_key() . ' = ?';
				break;
			case ($options->type == 'has-many' || $options->type == 'has-one'):
				$ret = $options->alias . "." . $options->foreign_key . " = ?";
				break;
		}
		if($options->thru != ''){
			$klass = $options->thru;
			$klass = new $klass;
			$options->join = ' INNER JOIN `' . $klass->table_name() . '` AS ' . $klass->alias() . '
									ON ' . $klass->alias() . '.' . Inflections::foreignKey($options->table) . ' = ' . $options->alias . '.id';
			$ret = $klass->alias() . '.' . $options->foreign_key . ' = ?';
		}
		return $ret;
	}
} // END class Schema
