<?php
/**
 * ActiveRecord
 *
 * @package db
 * @author Justin Palmer
 */				
class ActiveRecord extends SqlBuilder
{
	/**
	 * PDOStatement
	 * 
	 * @var PDOStatement
	 */
	private $Statement;
	
	/**
	 * Save
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function save()
	{
		$primary = $this->primary_key();
		if($this->$primary === null){
			if(!$this->filter('beforeCreate')) return false;
			if(!$this->create()) return false;
			if(!$this->filter('afterCreate')) return false;
		}else{
			if(!$this->filter('beforeUpdate')) return false;
			if(!$this->update()) return false;
			if(!$this->filter('afterUpdate')) return false;
		}
		return true;
	}
	
	/**
	 * insert
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function create()
	{
		$this->from($this->database_name(), $this->table_name());
		$query = $this->build(DatabaseAdapter::CREATE);
		return $this->processCud($query);
	}
	
	/**
	 * update
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function update()
	{
		$this->from($this->database_name(), $this->table_name());
		$query = $this->build(DatabaseAdapter::UPDATE);
		return $this->processCud($query);
	}
	
	/**
	 * Find the id's specified and with the primary key
	 * 
	 * If no id's and no primary key in the model throw ActiveRecordNoIdException
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
	public function find(/* id's */)
	{
		$args = func_get_args();
		$forceArray = false;
		$primary = $this->primary_key();
		if($this->$primary !== null)
			$args[] = $this->$primary;
	 	if(sizeof($args) > 0){
			if(sizeof($args) > 1) $forceArray = true;
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
	public function findAll()
	{
		return $this->processRead($this->build(DatabaseAdapter::READ), true);
	}
	
	/**
	 * findFirst
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function findFirst()
	{
		$this->limit(1);
		return $this->processRead($this->build(DatabaseAdapter::READ), false);
	}
	
	/**
	 * findOrCreateBy
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function findOrCreateBy()
	{
		
	}
	
	/**
	 * count records
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function count($column='*', $as='count', $distinct=null)
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
	public function delete(/* id's */)
	{
		$args = func_get_args();
		$primary = $this->primary_key();
		if($this->$primary !== null)
			$args[] = $this->$primary; 	
 		if(sizeof($args) > 0){
			if(sizeof($args) > 1) $forceArray = true;
			$question_marks = $this->getQuestionMarks($args);
			$this->where("$primary IN ($question_marks)", $args);
		}
		$this->from($this->database_name(), $this->table_name());
		$query = $this->build(DatabaseAdapter::DELETE);
		return $this->processCud($query);
	}
	
	/**
	 * Call a dynamic finder
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function __call($method, $params)
	{
		$finder = $this->findDynamicFinder($method);
		
		$underscore = Inflections::underscore($finder->props);
		
		$and = explode('_and_', $underscore);
		$where = implode(' = ?  AND ', $and);
		
		$or = explode('_or_', $where);
		if(sizeof($and) > 1 and sizeof($or) > 1)
			throw new Exception('no and/or combo');
			
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
	 * See if the dynamic finder is available.
	 * Only used by __call
	 *
	 * @param string $method - Method from __call
	 * @return stdClass
	 * @author Justin Palmer
	 **/
	private function findDynamicFinder($method)
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
			throw new Exception('No dynamic finder found :(');
		return $finder;
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
			$prop = $query->prop;
			$this->select($query->alias .".*")
				 ->from($this->database_name(), $query->table, $query->alias)
				 ->where($query->where . $query->on, $this->$prop)
				 ->order($query->order_by);
			$sqlObject = $this->build(DatabaseAdapter::READ);
			//Function to set the fetchmode for the class
			$customFetchMode = function($statement, $klass){
				$statement->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, $klass);
			};
			//What should we return.
			if($query->type == 'has-one' || $query->type == 'belongs-to'){
				$result->$key = $this->processRead($sqlObject, false, $customFetchMode, $query->klass);
				//if no record is found then set null
				if(!$result->$key) $result->$key = null;
			}else{
				$result->$key = $this->processRead($sqlObject, true, $customFetchMode, $query->klass);
			}
		}	
		return ($isLazy) ? $result->$key : $result;
	}
	
	/**
	 * create comma separate question marks for the size of the array
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
	 * @author Justin Palmer
	 **/
	private function processRead($object, $forceArray = false, $customFetchMode=null, $customFetchClass=null)
	{
		$this->reset();
		$this->Statement = $this->conn()->prepare($object->sql);
		$this->setFetchMode($customFetchMode, $customFetchClass);
		$this->Statement->execute(array_values($object->params));
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
		$this->Statement->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, get_class($this));
		if($this->isRaw()){
			$this->Statement->setFetchMode(PDO::FETCH_OBJ);
		}elseif($custom instanceof Closure){
			$custom($this->Statement, $customClass);
		}
		//return raw to it's original state.
		$this->raw(false);
	}
}
