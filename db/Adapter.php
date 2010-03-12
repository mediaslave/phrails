<?php
/**
 * Database adapter
 *
 * @todo There is a lot to do with relationships (Schema class) and how to deal with them.
 * It is really hard coded in now as a of sorts.
 * 
 * @package db
 * @author Justin Palmer
 */				
class Adapter extends PDO
{	
	/**
	 * Implemented drivers
	 *
	 * @todo , 'sqlite'=>'Sqlite' support
	 * @author Justin Palmer
	 * @var array
	 */
	protected static $drivers = array('mysql'=>'Mysql');
	/**
	 * The config loaded from the database.ini
	 *
	 * @author Justin Palmer
	 * @var stdClass
	 */
	protected static $Config = null;
	/**
	 * builder object
	 *
	 * @author Justin palmer
	 * @var SqlBuilder
	 */
	public $builder;
	/**
	 * The current model
	 *
	 * @author Justin Palmer
	 * @var Model
	 */
	public $model;
	
	/**
	 * Constructor
	 *
	 * @return DatabaseAdapter
	 * @author Justin Palmer
	 **/
	public function __construct($model, $encoding=null)
	{
		
		//var_dump(PDO::getAvailableDrivers());
		$this->model = $model;
		$this->parentConstruct($encoding);
		//Register the adapter with the builder.
		$this->builder = new SqlBuilder($this->model);
	}
	
	/**
	 * Hack to get the right connection information to the pdo construct.
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function parentConstruct($encoding)
	{
		self::getConfig();
		self::getDriverClass();
		switch(self::$Config->driver){
			case 'sqlite':
				$driver = self::$Config->driver . ":" . self::$Config->database;
				parent::__construct($driver);
				break;
			case 'mysql':
				parent::__construct(self::$Config->driver . ":host=" . self::$Config->host . ";dbname=" . self::$Config->database, 
									self::$Config->username, 
									self::$Config->password, 
									$encoding);
				break;
			default:
				throw new Exception("Database driver: '" . self::$Config->driver . "' is unknown.");
		}
	}
	/**
	 * Get all of the column data.
	 *
	 * @return mixed
	 * @author Justin Palmer
	 **/
	public function showColumns($query)
	{
		$this->Statement = $this->prepare($query);
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
		$table_name = $this->model->table_name();
		$primary_key = $this->model->alias() . '.' . $this->model->primary_key();
		$primary = " ($primary_key = ?)";
		if(!empty($this->builder->conditions)){
			$and = ($this->builder->conditions[0] != '') ? ' AND' : '';
			$this->builder->conditions[0] = $this->builder->conditions[0] . $and . $primary;
		}else{
			$this->builder->conditions[] = $primary;
		}
		$this->builder->conditions[] = $id;
		$query = $this->builder->build("SELECT ? FROM `$table_name`");
		$this->builder->reset();
		$this->Statement = $this->prepare(array_shift($query->query));
		$params = $query->params;
		$this->Statement->execute($query->params);
		try{
			//$model = get_class($this->model);
			$result = ResultFactory::factory($this->Statement);
			foreach($query->query as $key => $query){
				//print $key;
				$key = Inflections::pluralize($key);
				$stmt = $this->prepare($query);
				//print $stmt->queryString . "<br/>";
				//var_dump($query->params);
				$stmt->execute($params);
				$result->$key = ResultFactory::factory($stmt, true);
			}
		}catch(RecordNotFoundException $e){
			throw $e;
		}
		return $result;
	}
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function findAll($forceSet=true)
	{
		$table_name = $this->model->table_name();
		$query = $this->builder->build("SELECT ? FROM `$table_name`");
		$this->builder->reset();
		$this->Statement = $this->prepare(array_shift($query->query));
		$this->Statement->execute(array_values($query->params));
		return ResultFactory::factory($this->Statement, $forceSet);
	}
	
	/**
	 * Save the model record
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function saveNow()
	{
		$table_name = $this->model->table_name();
		$primary_key_name = $this->model->primary_key();
		$id = $primary_key_name;
		if($this->model->$id === null){
			$props = $this->model->props()->export();
			$columns = $this->getInsertColumnNames($props);
			$marks = $this->questionMarksByNum(sizeof($props));
			$this->Statement = $this->prepare(sprintf("INSERT INTO `$table_name` (%s) values (%s)", $columns, $marks));
			$params = array_values($props);
			return ($this->Statement->execute($params)) ? true : (object)$this->Statement->errorInfo();
		}else{
			$id = $this->model->$primary_key_name;	
			$this->model->removeProperty($primary_key_name);
			//Get the props before setting the primary key for the UpdateSet method
			$props = $this->model->props()->export();
			$query = "UPDATE `$table_name` SET %s WHERE `$primary_key_name` = ?";	
			$this->Statement = $this->prepare(sprintf($query, $this->getUpdateSet($props)));
			$this->model->$primary_key_name = $id;
			$params = array_values($this->model->props()->export());
			return ($this->Statement->execute($params)) ? true : false;
		}
	}
	/**
	 * Find anything by submitting pure sql.
	 *
	 * @return mixed
	 * @author Justin Palmer
	 **/
	public function findBySql($sql, $args=array(), $forceSet=true)
	{
		$args = func_get_args();
		//Shift out the sql var.
		$sql = array_shift($args);
		//Let's get the key of the forceSet var.
		$force_set_key = sizeof($args) - 1;
		//Set the forceSet var.
		$forceSet = $args[$force_set_key];
		//Unset the forceSet var, leaving us with just the args for execute().
		unset($args[$force_set_key]);
		//Prepare the sql.
		$this->Statement = $this->prepare($sql);
		//If the user passed in an array of args then well get the first one for the execute method.
		if(is_array($args[0]))
			$args = $args[0];
		//Execute the query.
		$this->Statement->execute($args);
		//Return the correct object for the situation.
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
	public static function getDriverClass()
	{
		$Config = self::getConfig();
		if(!array_key_exists($Config->driver, self::$drivers))
			throw new Exception("Database driver: '$Config->driver' is unknown.");
		return self::$drivers[$Config->driver];
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