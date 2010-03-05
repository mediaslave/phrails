<?php
/**
 * The base model.
 *
 * @package database
 * @author Justin Palmer
 **/
abstract class Model
{
	public $primary_key = 'id';
	public $table_name;
	private $db;
	public $schema;
	//Holds data for the columns.
	public $props;
	//Holds the valid columns.
	public $columns;
	
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
		$this->table_name = Inflections::pluralize(strtolower(preg_replace('/([^\s])([A-Z])/', '\1_\2', get_class($this))));
		$this->props = new Hash($array);
		$this->schema = new Schema($this->model);
		
		
		$this->db = new $Adapter($this);
		
		$this->columns = $this->prepareShowColumns($this->showColumns());
		$this->schema();
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
		}else if(method_exists($this->db->Builder, $method)){
			$object = $this->db->builder;
		}else if(method_exists($this->schema, $method)){
			$object = $this->schema;
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
			throw new Exception("Trying to set invalid column name: $key for table: $this->table_name");
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
	 * init
	 *
	 * @return mixed
	 * @author Justin Palmer
	 **/
	abstract public function schema();
} // END class Model