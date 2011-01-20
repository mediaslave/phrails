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
class Adapter
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
	
	public $pdo;
	
	public $raw=false;
	
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
		$this->createPdo($encoding);
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
	public function createPdo($encoding)
	{
		self::getConfig();
		self::getDriverClass();
		try{
			switch(self::$Config->driver){
				/*case 'sqlite':
					break;
				*/
				case 'mysql':
					$this->pdo = new PDO(self::$Config->driver . ":host=" . self::$Config->host . ";dbname=" . self::$Config->database, 
										 self::$Config->username, 
										 self::$Config->password, 
									 	$encoding);
					break;
				default:
					throw new Exception("Database driver: '" . self::$Config->driver . "' is unknown.");
			}
		} catch (PDOException $e) {
			die($e->getMessage());
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
		$this->Statement = $this->pdo->prepare($query);
		$this->Statement->setFetchMode(PDO::FETCH_OBJ);
		$this->Statement->execute();
		return $this->Statement->fetchAll();
	}
	
	/**
	 * Set the fetchmode for the prepare query.
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	private function setFetchMode()
	{
		if($this->raw){
			$this->Statement->setFetchMode(PDO::FETCH_OBJ);
		}else{
			$this->Statement->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, get_class($this->model));
		}
		//return raw to it's original state.
		$this->raw(false);
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
		$this->builder->model = $this->model;
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
		if($this->builder->isRawMode())
			$this->raw();
		$this->builder->reset();
		$this->Statement = $this->pdo->prepare(array_shift($query->query));
		$this->setFetchMode();
		$this->Statement->execute($query->params);
		$result = $this->Statement->fetch();
		if(!$result)
			throw new RecordNotFoundException($this->lastPreparedQuery(), $query->params);
		$result = $this->lazy($result, $query->query);
		return $result;
	}
	/**
	 * Add the joins to the result
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function lazy($result, $joins, $isLazy=false)
	{	
		foreach($joins as $key => $query){
			//new Dbug($query, '', false, __FILE__, __LINE__);
			$prop = $query->prop;
			$prepare = "SELECT " . $query->alias . ".* FROM `" . $query->table . "` as " . $query->alias . " " . $query->join . " 
								WHERE " . $query->where . $query->on . $query->order_by;
			//new Dbug($prepare, '', false, __FILE__, __LINE__);
			$stmt = $this->pdo->prepare($prepare);
			$stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, $query->klass);
			$stmt->execute(array($result->$prop));
			if($query->type == 'has-one' || $query->type == 'belongs-to'){
				$result->$key = $stmt->fetch();
				//if no record is found then set null
				if(!$result->$key) $result->$key = null;
			}else{
				$result->$key = $stmt->fetchAll();
			}
		}	
		return ($isLazy) ? $result->$key : $result;
	}
	
	/**
	 * Count the number of records
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function count($column='*', $isDistinct=false, $as='count')
	{
		$select = 'COUNT(' . $column . ')';
		if($isDistinct)
			$select = 'COUNT(DISTINCT ' . $column . ')';
		$this->raw();
		$this->builder->select($select . ' AS ' . $as);
		return $this->model;
	}
	
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function findAll($forceArray = true)
	{
		$database_name = $this->model->database_name();
		$table_name = $this->model->table_name();
		$query = $this->builder->build("SELECT ? FROM `$database_name`.`$table_name`");
		if($this->builder->isRawMode())
			$this->raw();
		$this->builder->reset();
		$this->Statement = $this->pdo->prepare(array_shift($query->query));
		$this->setFetchMode();
		$this->Statement->execute(array_values($query->params));
		if($forceArray == false && $this->Statement->rowCount() == 1){
			return $this->Statement->fetch();
		}
		return $this->Statement->fetchAll();
	}

	public function delete()
	{
		$database_name = $this->model->database_name();
		$table_name = $this->model->table_name();
		$query = $this->builder->build("DELETE FROM `$database_name`.`$table_name`", true);

		$this->builder->reset();
		$this->Statement = $this->pdo->prepare(array_shift($query->query));

		return $this->Statement->execute(array_values($query->params));
    
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
			$this->Statement = $this->pdo->prepare(sprintf("INSERT INTO `$database_name`.`$table_name` (%s) values (%s)", $columns, $marks));
			
			$params = array_values($this->model->props()->export());
			if($this->Statement->execute($params)){
				$ret = true;
				$this->model->$primary_key_name = $this->pdo->lastInsertId();
			}else{
				$ret = false;
				$error = $this->Statement->errorInfo();
				throw new SqlException('Error: ' . $error[0] . ' - ' . $error[2], $error[0]);
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
			$this->Statement = $this->pdo->prepare(sprintf($query, $this->getUpdateSet($props)));
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
		$this->Statement = $this->pdo->prepare($sql);
		//If the user passed in an array of args then well get the first one for the execute method.
		if(!empty($args) && is_array($args[0]))
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
			//print $key . ' = ';var_dump($value); print '<br>';
			//var_dump(in_array($key, $this->model->props_changed())); print '<br>';
			if(in_array($key, $this->model->props_changed()) && $value !== null){
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
			if(in_array($key, $this->model->props_changed()) && $value !== null)
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
		foreach(array_keys($props) as $key){
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
	 * Set the fetchmode to take into account the class or not
	 * 
	 * If not, it will return the raw results from the pdo
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function raw($value=true)
	{
		$this->raw = $value;
		return $this->model;
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
	 * Is the given $value unique in $column ?
	 *
	 * @author Dave Kerschner (dkerschner@cetusa.org)
	 * @access public
	 * 
	 * @return boolean
	 */
  public function isUnique($column, $value) {
    $u = $this->model->where('`' . $column . '` = ?', $value)->findAll(false);
		return $u instanceof $this->model ? false : true;
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