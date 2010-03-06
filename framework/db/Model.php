<?php
/**
 * The base model.
 *
 * 
 * @package database
 * @author Justin Palmer
 **/
abstract class Model
{
	protected $primary_key = 'id';
	protected $table_name;
	protected $alias;
	protected $db;
	protected $schema;
	//Holds data for the columns.
	protected $props;
	//Holds the valid columns.
	protected $columns;
	protected $errors = array();
	
	/**
	 * Constructor
	 *
	 * @param array $array
	 * @return Model
	 * @author Justin Palmer
	 **/
	public function __construct(array $array=array())
	{
		$Config = Registry::get('pr-db-config');
		$Adapter = ucwords($Config->driver);
		Adapter::checkDriver();
		//Generate the table name if it is not set.
		if($this->table_name === null)
			$this->table_name = Inflections::tableize(get_class($this));
		$this->props = new Hash($array);
		$this->schema = new Schema($this);
		$this->alias = $this->table_name;
		//Store the db adapter.
		$this->db = new $Adapter($this);
		//Hold the columns from the db to make sure properties, rules and relationships set actually exist.
		$this->columns = $this->prepareShowColumns($this->showColumns());
		$this->init();
	}
	/**
	 * Export the current model object to array.
	 *
	 * @return array
	 * @author Justin Palmer
	 **/
	public function export()
	{
		return $this->props->export();
	}
	/**
	 * Remove a property.
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function removeProperty($key)
	{
		$this->props->remove($key);
	}
	/**
	 * Overload the save method in the db so that we can run validation
	 *
	 * @todo add error messages to some where.
	 * @return boolean
	 * @author Justin Palmer
	 **/
	public function save()
	{
		$boolean = $this->validate();
		if($boolean){
			return $this->db->saveNow();
		}else{
			throw new RecordInvalidException;
		}
	}
	/**
	 * Validate the model
	 *
	 * @return boolean
	 * @author Justin Palmer
	 **/
	public function validate()
	{
		$boolean = true;
		//Run validation before calling save.
		$rules = $this->schema->rules();
		foreach($rules as $model_value => $element_rules){
			foreach($element_rules as $rule){
				//Set the value of the property in the model to the value of the rule
				//to run the validation on.
				$rule->value = $this->$model_value;
				if(!$rule->run()){
					if($boolean)
						$boolean = false;
					//Add the error message to some sort of array. So that we can add it to a flash.
					$this->errors[] = $rule->message;
				}
			}
		}
		return $boolean;
	}
	/**
	 * Does this model have validation errors
	 *
	 * @return boolean
	 * @author Justin Palmer
	 **/
	public function hasErrors()
	{
		return (empty($this->errors)) ? false : true;
	}
	/**
	 * Turn showColumns call into a hash.
	 *
	 * @return Hash
	 * @author Justin Palmer
	 **/
	public function prepareShowColumns(ResultSet $ResultSet)
	{
		$array = array();
		foreach($ResultSet as $value){
			$array[$value->Field] = $value;
		}
		return new Hash($array);
	}
	/**
	 * Call the method on the db if it does not exist here
	 *
	 * @return mixed
	 * @author Justin Palmer
	 **/
	public function __call($method, $arguments=array())
	{
		//print('we tried to call' . $method);
		$object = null;
		if(method_exists($this->db, $method)){
			$object = $this->db;
		}else if(method_exists($this->db->builder, $method)){
			$object = $this->db->builder;
		}else{
			throw new Exception('We do not have that method. Tried to call: ' . $method);
		}
		return call_user_func_array(array($object, $method), $arguments);
	}

	/**
	 * __get data
	 *
	 * @return string
	 * @author Justin Palmer
	 **/
	public function __get($key)
	{
		return $this->props->get($key);
	}

	/**
	 * __set vars
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function __set($key, $value)
	{
		if(!$this->columns->isKey($key))
			throw new NoColumnInTableException();
		$this->props->set($key, $value);
	}
	/**
	 * Close the connection
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function __destruct()
	{
		$this->db = null;
	}
	/**
	 * Get the error array
	 *
	 * @return array
	 * @author Justin Palmer
	 **/
	public function errors()
	{
		return $this->errors;
	}
	/**
	 * Get the valid columns
	 *
	 * @return Hash
	 * @author Justin Palmer
	 **/
	public function columns()
	{
		return $this->columns;
	}
	/**
	 * Get the current properties set
	 *
	 * @return Hash
	 * @author Justin Palmer
	 **/
	public function props()
	{
		return $this->props;
	}
	/**
	 * Get the schema object.
	 *
	 * @return Schema
	 * @author Justin Palmer
	 **/
	public function schema()
	{
		return $this->schema;
	}
	/**
	 * Get the current db connection
	 *
	 * @return Adapter
	 * @author Justin Palmer
	 **/
	public function db()
	{
		return $this->db;
	}
	/**
	 * Get the alias
	 *
	 * @return string
	 * @author Justin Palmer
	 **/
	public function alias()
	{
		return $this->alias;
	}
	/**
	 * Get the table name.
	 *
	 * @return string
	 * @author Justin Palmer
	 **/
	public function table_name()
	{
		return $this->table_name;
	}
	/**
	 * Get the primary_key value
	 *
	 * @return string
	 * @author Justin Palmer
	 **/
	public function primary_key()
	{
		return $this->primary_key;
	}
	/**
	 * init
	 *
	 * @return mixed
	 * @author Justin Palmer
	 **/
	abstract public function init();
} // END class Model