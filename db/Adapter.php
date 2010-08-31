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
		//$this->setAttribute(PDO::ATTR_STATEMENT_CLASS, array('Statement', array($this)));
		//Register the adapter with the builder.
		$this->builder = new SqlBuilder($model);
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
			/*case 'sqlite':
				$driver = self::$Config->driver . ":" . self::$Config->database;
				parent::__construct($driver);
				break;
			*/
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
		return ResultFactory::factory($this->Statement, true);
	}
	/**
	 * Find by primary key
	 * 
	 * @todo No Builder direct access to conditions
	 * @return void
	 * @author Justin Palmer
	 **/
	public function find($id=null)
	{
		if($id == null){
			$primary_key = $this->model->primary_key();
			if($this->model->$primary_key == null){
				throw new Exception('Model::find did not receive an id and the model does not have the id.');
			}else{
				$id = $this->model->$primary_key;
			}
		}
		$database_name = $this->model->database_name();
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
		$query = $this->builder->build("SELECT ? FROM `$database_name`.`$table_name`");
		$this->builder->reset();
		$this->Statement = $this->prepare(array_shift($query->query));
		$params = $query->params;
		$this->Statement->execute($query->params);
		try{
			//$model = get_class($this->model);
			$result = ResultFactory::factory($this->Statement);
			foreach($query->query as $key => $query){
				//print $key;
				$stmt = $this->prepare($query);
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
		$database_name = $this->model->database_name();
		$table_name = $this->model->table_name();
		$query = $this->builder->build("SELECT ? FROM `$database_name`.`$table_name`");
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
		$database_name = $this->model->database_name();
		$table_name = $this->model->table_name();
		$primary_key_name = $this->model->primary_key();
		$id = $primary_key_name;
		//var_dump($this->model->$id);
		if($this->model->$id === null){
			try{
				$this->model->created_at = new Expression('NOW()');
			}catch(NoColumnInTableException $e){}
			//print 'insert' . '<br/>';
			$props = $this->model->props()->export();
			$columns = $this->getInsertColumnNames($props);
			$marks = $this->getValues($props);
			$this->Statement = $this->prepare(sprintf("INSERT INTO `$database_name`.`$table_name` (%s) values (%s)", $columns, $marks));
			
			$params = array_values($this->model->props()->export());
			if($this->Statement->execute($params)){
				$ret = true;
				$this->model->$primary_key_name = $this->lastInsertId();
			}else{
				$ret = $this->Statement->errorInfo();
			}
			return $ret;
		}else{
			try{
				$this->model->updated_at = new Expression('NOW()');
			}catch(NoColumnInTableException $e){}
			//print 'update' . '<br/>';
			$id = $this->model->$primary_key_name;	
			$this->model->removeProperty($primary_key_name);
			//Get the props before setting the primary key for the UpdateSet method
			$props = $this->model->props()->export();
			$query = "UPDATE `$database_name`.`$table_name` SET %s WHERE `$primary_key_name` = ?";	
			$this->Statement = $this->prepare(sprintf($query, $this->getUpdateSet($props)));
			$this->model->$primary_key_name = $id;
			return ($this->Statement->execute($this->getUpdateValues())) ? true : false;
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
		//Set the forceSet var.
		$forceSet = array_pop($args);
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
			if(in_array($key, $this->model->props_changed())){
				if($value instanceof Expression){
					$return .= "`$key` = $value,";
					$this->model->props()->remove($key);
				}else{
					$return .= "`$key` = ?,";
				}
			}
		}
		return rtrim($return, ',');
	}
	/**
	 * Get the values that are in the changed props of the model.
	 *
	 * @return array
	 * @author Justin Palmer
	 **/
	public function getUpdateValues()
	{
		$ret = array();
		$params = $this->model->props()->export();
		foreach($params as $key => $value){
			if(in_array($key, $this->model->props_changed()))
				$ret[] = $value;
		}
		return $ret;
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
	 * build values
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function getValues($props)
	{
		$return = '';
		foreach($props as $key => $value){
			if($value instanceof Expression){
				$return .= "$value,";
				$this->model->props()->remove($key);
			}else{
				$return .= "?,";
			}
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