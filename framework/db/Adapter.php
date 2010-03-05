<?php
/**
 * Database adapter
 *
 * @package database
 * @author Justin Palmer
 */				
class Adapter extends PDO
{	
	protected static $drivers = array('mysql');
	
	protected static $Config = null;
	
	public $builder;
	public $model;
	
	/**
	 * Constructor
	 *
	 * @return DatabaseAdapter
	 * @author Justin Palmer
	 **/
	public function __construct($model, $encoding)
	{
		self::getConfig();
		self::checkDriver();
		$this->model = $model;
		parent::__construct(self::$Config->driver . ":host=" . self::$Config->host . ";dbname=" . self::$Config->database, 
							self::$Config->username, 
							self::$Config->password, 
							$encoding);
		//Register the adapter with the builder.
		$this->builder = new SqlBuilder($this->model);
	}
	/**
	 * Get all of the column data.
	 *
	 * @return mixed
	 * @author Justin Palmer
	 **/
	public function showColumns()
	{
		$table_name = $this->model->table_name;
		$this->Statement = $this->prepare("SHOW COLUMNS FROM `$table_name`");
		$this->Statement->execute();
		return ResultFactory::factory($this->Statement);
	}
	/**
	 * Find by primary key
	 * 
	 * @todo No Builder direct access to conditions
	 * @return void
	 * @author Justin Palmer
	 **/
	public function find($id)
	{
		$table_name = $this->model->table_name;
		$primary_key = $this->model->primary_key;
		$primary = " ($primary_key = ?)";
		if(!empty($this->builder->conditions)){
			$and = ($this->builder->conditions[0] != '') ? ' AND' : '';
			$this->builder->conditions[0] = $this->builder->conditions[0] . $and . $primary;
		}else{
			$this->builder->conditions[] = $primary;
		}
		$this->builder->conditions[] = $id;
		$query = $this->builder->build("SELECT ? FROM `$table_name`");
		$this->Statement = $this->prepare($query->query);
		$this->Statement->execute($query->params);
		return ResultFactory::factory($this->Statement);
	}
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function findAll()
	{
		$table_name = $this->model->table_name;
		$query = $this->builder->build("SELECT ? FROM `$table_name`");
		$this->Statement = $this->prepare($query->query);
		$this->Statement->execute(array_values($query->params));
		return ResultFactory::factory($this->Statement, true);
	}
	
	/**
	 * Save the model record
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function save()
	{
		$table_name = $this->model->table_name;
		$primary_key_name = $this->model->primary_key;
		$id = $this->model->$primary_key_name;
		if($id == null){
			$props = $this->model->props->export();
			$columns = $this->getInsertColumnNames($props);
			$marks = $this->questionMarksByNum(sizeof($props));
			$this->Statement = $this->prepare(sprintf("INSERT INTO `$table_name` (%s) values (%s)", $columns, $marks));
			$params = array_values($props);
			return ($this->Statement->execute($params)) ? true : (object)$this->Statement->errorInfo();
		}else{	
			$this->model->removeProperty($primary_key_name);
			//Get the props before setting the primary key for the UpdateSet method
			$props = $this->model->props->export();
			$this->model->$primary_key_name = $id;
			$query = "UPDATE `$table_name` SET %s WHERE `$primary_key_name` = ?";	
			$this->Statement = $this->prepare(sprintf($query, $this->getUpdateSet($props)));
			$params = array_values($this->model->props->export());
			return ($this->Statement->execute($params)) ? true : (object)$this->Statement->errorInfo();
		}
	}
	/**
	 * Find anything by submitting pure sql.
	 *
	 * @return mixed
	 * @author Justin Palmer
	 **/
	public function findBySql($sql, $forceSet=true)
	{
		$this->Statement = $this->prepare($sql);
		$this->Statement->execute();
		return ResultFactory::factory($this->Statement, $forceSet);
	}
	/**
	 * get the SQL SET declaration for the 
	 *
	 * @return string
	 * @author Justin Palmer
	 **/
	private function getUpdateSet($props)
	{
		$return = '';
		foreach($props as $key => $value){
			$return .= "`$key` = ?,";
		}
		return rtrim($return, ',');
	}
	/**
	 * Get the column names in a list for insert
	 *
	 * @return string
	 * @author Justin Palmer
	 **/
	private function getInsertColumnNames($props)
	{
		$return = '';
		foreach($props as $key => $value){
			$return .= "`$key`,";
		}
		return rtrim($return, ',');
	}
	/**
	 * Get the correct number of ?marks back to put into the string.
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	private function questionMarksByNum($num)
	{
		$return = '';
		for($i = 0; $i < $num; $i++){
			$return .= '?,';
		}
		return rtrim($return, ',');
	}
	/**
	 * Get the last query ran.
	 *
	 * @return string
	 * @author Justin Palmer
	 **/
	public function lastPreparedQuery()
	{
		return $this->Statement->queryString;
	}
	/**
	 * Confirm that the driver is a valid driver.
	 *
	 * @return boolean
	 * @author Justin Palmer
	 **/
	public static function checkDriver()
	{
		$Config = self::getConfig();
		if(!in_array($Config->driver, self::$drivers))
			throw new Exception("Database driver: '$Config->driver' is unknown.");
	}
	
	/**
	 * Get the config for the db.
	 *
	 * @return stdClass
	 * @author Justin Palmer
	 **/
	public static function getConfig()
	{
		$Config = self::$Config;
		if(self::$Config === null) 
			self::$Config = $Config = Registry::get('pr-db-config');
		return $Config;
	}
}