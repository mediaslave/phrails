<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
 * @package db
 */
/**
 * ActiveRecord
 *
 * @package db
 */
class ActiveRecord extends SqlBuilder
{
	public static $num_queries = 0;
	/**
	 * PDOStatement
	 *
	 * @var PDOStatement
	 */
	private $Statement;

	/**
	 * Store the last query that was ran.
	 *
	 * @var stdClass
	 */
	 static private $last_query=null;
	/**
	 * Save
	 *
	 * @return void
	 * @throws FailedActiveRecordCreateUpdateException
	 * @author Justin Palmer
	 **/
	public function save()
	{
		try{
			$this->adapter()->beginTransaction();
			$this->filter('beforeSave');
			$primary = $this->primary_key();
			if($this->$primary === null){
				if(!$this->create()) throw new FailedActiveRecordCreateUpdateException();
			}else{
				if(!$this->update()) throw new FailedActiveRecordCreateUpdateException();
			}
			$this->filter('afterSave');
			$this->adapter()->commit();
			$this->filter('afterCommit');
		}catch(Exception $e){
			$this->adapter()->rollBack();
			throw $e;
		}
		return true;
	}
	/**
	 * insert
	 *
	 * Careful no validation!
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	final public function create()
	{
		$this->filter('beforeCreate');
		$primary = $this->primary_key();
		$this->from($this->database_name(), $this->table_name());
		if($this->columns->isKey('created_at')){
			$this->created_at = date('Y-m-d H:i:s');
		}
		$query = $this->build(DatabaseAdapter::CREATE);
		$boolean = $this->processCud($query);
		$this->$primary = $this->lastInsertId();
		$this->filter('afterCreate');
		return $boolean;
	}
	/**
	 * update
	 *
	 * Careful no validation!
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	final public function update()
	{
		$this->filter('beforeUpdate');
		$primary = $this->primary_key();
		$p_value = $this->$primary;
		$this->$primary = null;
		$this->from($this->database_name(), $this->table_name());
		$this->where("$primary = ?", $p_value);
		if($this->columns->isKey('updated_at')){
			$this->updated_at = date('Y-m-d H:i:s');
		}
		$query = $this->build(DatabaseAdapter::UPDATE);
		$this->$primary = $p_value;
		$boolean = $this->processCud($query);
		$this->filter('afterUpdate');
		return $boolean;
	}

	/**
	 * truncate a table
	 *
	 * @return boolean
	 * @author Justin Palmer
	 **/
	public function truncate()
	{
		return $this->adapter()->truncate($this->table_name());
	}
	/**
	 * Update the props passed in.
	 *
	 * @return void
	 * @throws ActiveRecordInvalidColumnsForUpdateException
	 * @author Justin Palmer
	 **/
	final public function updateProps(/* properties */)
	{
		$id = $this->primary_key();
		$args = func_get_args();
		if(sizeof($args) == 0)
			throw new Exception('updateProps expects that you pass the properties you would like to update to it.');

		//Turn args into keys of an array to do some diff and intersect on it.
		$args_as_keys = array_fill_keys($args, null);
		//Find invalid columns and throw an exception if there are some
		$invalid_columns = array_diff_key($args_as_keys, $this->props()->export());
		if(sizeof($invalid_columns) > 0)
			throw new ActiveRecordInvalidColumnsForUpdateException(get_class($this), array_keys($invalid_columns));

		$props = array_intersect_key($this->props()->export(), $args_as_keys);
		$model = get_class($this);
		$model = new $model($props);
		$model->$id = $this->$id;
		$saved = $model->save();
		$this->errors($model->errors());
		return $saved;
	}
	/**
	 * Find the id's specified and with the primary key
	 *
	 * Example:
	 * <code>
	 * Person::noo()->where('active = ?', 1)->find(23)
	 * Person::noo()->find(1,2,3,4)
	 * </code>
	 *
	 * @todo $this->where("$primary IN...") does not use the adapter and should to allow
	 * nosql and other adapters to modify how that works.
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	final public function find(/* id's */)
	{
		$args = func_get_args();
		$forceArray = false;
		$primary = $this->primary_key();
		if($this->$primary !== null)
			$args[] = $this->$primary;
	 	if(count($args) > 0){
			if(count($args) > 1) $forceArray = true;
			$question_marks = $this->getQuestionMarks($args);
			$this->where("$primary IN ($question_marks)", $args);
		}
		return $this->processRead($this->build(DatabaseAdapter::READ), $forceArray);
	}
	/**
	 * Find all records
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	final public function findAll()
	{
		return $this->processRead($this->build(DatabaseAdapter::READ), true);
	}
	/**
	 * findFirst
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	final public function findFirst()
	{
		$this->limit(1);
		return $this->processRead($this->build(DatabaseAdapter::READ), false);
	}
	/**
	 * count records
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	final public function count($column='*', $as='count', $distinct=null)
	{
		$this->raw();
		$this->select('');
		parent::count($column, $as, $distinct);
		return $this->find();
	}
	/**
	 * delete records
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	final public function delete(/* id's */)
	{
		//Prepare the query with the given id's passed in
		$args = func_get_args();
		$primary = $this->primary_key();
		if($this->$primary !== null)
			$args[] = $this->$primary;
 		if(sizeof($args) > 0){
			$question_marks = $this->getQuestionMarks($args);
			$this->where("$primary IN ($question_marks)", $args);
		}
		$this->from($this->database_name(), $this->table_name());
		$query = $this->build(DatabaseAdapter::DELETE);

		//Transactional safe delete.
		try{
			$this->adapter()->beginTransaction();
			$this->filter('beforeDelete');
			if(!$this->processCud($query)) throw new FailedActiveRecordDeleteException();
			$this->filter('afterDelete');
			$this->adapter()->commit();
		}catch(Exception $e){
			$this->adapter()->rollBack();
			return false;
		}
		return true;
	}
	/**
	 * Get the last insert id.
	 *
	 * @return int
	 * @author Justin Palmer
	 **/
	final public function lastInsertId()
	{
		return $this->adapter()->lastInsertId();
	}
	/**
	 * Get the last query
	 *
	 * @return stdClass
	 * @throws Exception
	 * @author Justin Palmer
	 **/
	final public function lastQuery()
	{
		if(self::$last_query === null){
			throw new Exception('No queries have been recorded.');
		}
		return self::$last_query;
	}

	/**
	 * findBySql
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function findBySql($query, $args, $forceArray=false)
	{
		self::$num_queries++;
		$object = (object) array('sql'=>$query, 'params'=>$args);
		return $this->processRead($object, $forceArray);
	}

	/**
	 * Run a sql command
	 * 
	 * @return boolean
	 */
	public function sql($sql /*$param, $param, $param, $param */){
		self::$num_queries++;
		$args = func_get_args();
		$sql = array_shift($args);
		$object = (object) array('sql'=>$sql, 'params'=>$args);
		return $this->processCud($object);
	}

	/**
	 * Call a dynamic finder
	 *
	 * @return void
	 * @throws Exception
	 * @author Justin Palmer
	 **/
	final public function __call($method, $params)
	{
		try {
			return $this->findByDynamicFinder($method, $params);
		} catch (UnknownActiveRecordDynamicFinderException $e) {
			if(!$this->schema->relationships->isKey($method)){
				throw $e;
			}
			$this->schema->setLastRelationship($method)->whereParams($params);
			return $this->lazy($this, array($method=>$this->schema->relationships->get($method)), true);
		}
	}
	/**
	 * Join the tables passed in based off the Schema.
	 *
	 * @return void
	 * @throws NoSchemaRelationshipDefinedException
	 * @author Justin Palmer
	 **/
	final public function join($args)
	{
		$args = func_get_args();
		foreach($args as $key){
			if(!$this->schema()->relationships->isKey($key))
				throw new NoSchemaRelationshipDefinedException($this->table_name(), $key);
			$this->addJoinFromRelationship($this->schema()->relationships->get($key));
		}
		return $this;
	}
	/**
	 * Add the joins to the result
	 *
	 * @return void
	 * @throws RecordNotFoundException
	 * @author Justin Palmer
	 **/
	final protected function lazy($result, $joins, $isLazy=false)
	{
    	$this->reset();
		foreach($joins as $key => $query){
			$klass = $query->klass;
			$klass = new $klass();
			$prop = $query->prop;
			$whereParams = $query->whereParams;
			$whereParams[] = $this->$prop;
			$obj = $this->select($query->alias .".*");
			$obj = parent::join($query->join)
				 		->from($klass->database_name(), $klass->table_name(), $query->alias)
				 		->where($query->where . $query->on, $whereParams)
				 		->order($query->order_by);

			$sqlObject = $this->build(DatabaseAdapter::READ);
			//Function to set the fetchmode for the class
			$customFetchMode = function($statement, $klass){
				$statement->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, $klass);
			};
			//What should we return.
			if($query->type == 'has-one' || $query->type == 'belongs-to'){
				try{
					$result->$key = $this->processRead($sqlObject, false, $customFetchMode, $query->klass);
				}catch(RecordNotFoundException $e){
					$result->$key = null;
				}
			}else{
				$result->$key = $this->processRead($sqlObject, true, $customFetchMode, $query->klass);
			}
		}
		return ($isLazy) ? $result->$key : $result;
	}
	/**
	 * See if the dynamic finder is available.
	 * Only used by __call
	 *
	 * @param string $method - Method from __call
	 * @return stdClass
	 * @throws UnknownActiveRecordDynamicFinderException
	 * @author Justin Palmer
	 **/
	private function findByDynamicFinder($method, $params)
	{
		$registered_finders = array('findFirst', 'find', 'findAll', 'count', 'delete');
		$finder = null;
		foreach(array_values($registered_finders) as $dynamic_finder){
			$length = strlen($dynamic_finder) + 2;
			if(substr($method, 0, $length) == $dynamic_finder . 'By'){
				$props = substr($method, $length);
				$finder = new stdClass;
				$finder->props = $props;
				$finder->method = $dynamic_finder;
			}
		}
		if($finder === null)
			throw new UnknownActiveRecordDynamicFinderException($method);
		$underscore = Inflections::underscore($finder->props);

		$and = explode('_and_', $underscore);
		$where = implode(' = ?  AND ', $and);

		$or = explode('_or_', $where);
		if(sizeof($and) > 1 and sizeof($or) > 1)
			throw new Exception('No and/or dynamic finders.');

		if((sizeof($and) == 1 && sizeof($or) == 1) && sizeof($params) > 1){
			$where = "$underscore IN (" . $this->getQuestionMarks($params) . ")";
		}else{
			$where = implode(' = ?  OR ', $or);
			$where .= ' = ?';
		}

		$this->where('(' . $where . ')', $params);
		$method = $finder->method;
		return $this->$method();
	}
	/**
	 * create comma separate question marks for the size of the array
	 *
	 * @todo should this be in the adapter?
	 *
	 * @return string
	 * @author Justin Palmer
	 **/
	private function getQuestionMarks($args)
	{
		$question_marks = '';
		for($i=0; $i < sizeof($args); $i++){
			$question_marks .= "?,";
		}
		return rtrim($question_marks, ',');
	}
	/**
	 * Process, execute the query and return the results.
	 *
	 * @param stdClass $object
	 * @param boolean $forceArray
	 * @return void
	 * @throws RecordNotFoundException
	 * @author Justin Palmer
	 **/
	private function processRead(stdClass $query, $forceArray = false, $customFetchMode=null, $customFetchClass=null)
	{
		self::$num_queries++;
		self::$last_query = $query;
		$this->reset();
		$this->Statement = $this->conn()->prepare($query->sql);
		$this->setFetchMode($customFetchMode, $customFetchClass);
		$this->Statement->execute(array_values($query->params));
		if($forceArray == false && $this->Statement->rowCount() == 0)
			throw new RecordNotFoundException($query->sql, $query->params);
		if($forceArray == false && $this->Statement->rowCount() == 1){
			return $this->Statement->fetch();
		}
		return $this->Statement->fetchAll();
	}
	/**
	 * process a C, U or D
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	private function processCud(stdClass $query)
	{
		self::$num_queries++;
		self::$last_query = $query;
		$this->Statement = $this->conn()->prepare($query->sql);
		return $this->Statement->execute($query->params);
	}
	/**
	 * Set the fetch mode for the prepare query.
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	private function setFetchMode($custom=null, $customClass=null)
	{
		$class = $customClass;
		if(is_null($customClass)){
			$class = get_class($this);
		}
		$this->Statement->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, $class);
		if($this->isRaw()){
			$this->Statement->setFetchMode(PDO::FETCH_OBJ);
		}elseif($custom instanceof Closure){
			$custom($this->Statement, $customClass);
		}
		//return raw to it's original state.
		$this->raw(false);
	}
}
