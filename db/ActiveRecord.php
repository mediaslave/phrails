<?php
/**
 * ActiveRecord
 *
 * @package db
 * @author Justin Palmer
 */				
class ActiveRecord extends SqlBuilder
{
	private $Statement;
	/**
	 * create
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	// static public function create()
	// 	{
	// 		
	// 	}
	
	/**
	 * Save
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function save()
	{
		
	}
	
	/**
	 * insert
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function insert()
	{
		
	}
	
	/**
	 * update
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function update()
	{
		
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
	 * @return void
	 * @author Justin Palmer
	 **/
	public function find(/* id's */)
	{
		$forceArray = false;
		 $args = func_get_args();
		 	$primary = $this->primary_key();
		if($this->$primary !== null)
			$args[] = $this->$primary;
		if(empty($args))
			throw new ActiveRecordNoIdException($this);	
	 	if(sizeof($args) > 1)
			$forceArray = true;
		$question_marks = '';
		for($i=0; $i < sizeof($args); $i++){
			$question_marks .= "?,";
		}
		$question_marks = rtrim($question_marks, ',');
		$this->where("$primary IN ($question_marks)", $args);
		return $this->process($this->build(DatabaseAdapter::READ), $forceArray);
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
			new Dbug($query, '', false, __FILE__, __LINE__);
			$prop = $query->prop;
			$this->select($query->alias .".*")
				 ->from($this->database_name(), $query->table, $query->alias)
				 ->where($query->where . $query->on, $this->$prop)
				 ->order($query->order_by);
			$sqlObject = $this->build(DatabaseAdapter::READ);
			$customFetchMode = function($statement, $klass){
				$statement->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, $klass);
			};
			if($query->type == 'has-one' || $query->type == 'belongs-to'){
				$result->$key = $this->process($sqlObject, false, $customFetchMode, $query->klass);
				//if no record is found then set null
				if(!$result->$key) $result->$key = null;
			}else{
				$result->$key = $this->process($sqlObject, true, $customFetchMode, $query->klass);
			}
		}	
		return ($isLazy) ? $result->$key : $result;
	}

	/**
	 * Process, execute the query and return the results.
	 *
	 * @param stdClass $object
	 * @param boolean $forceArray
	 * @return void
	 * @author Justin Palmer
	 **/
	public function process($object, $forceArray = false, $customFetchMode=null, $customFetchClass=null)
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
	 * findAllBy
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function findAllBy(/* $condtion, $options*/)
	{
		
	}
	
	/**
	 * findFirst
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function findFirst(/* $condition, $options*/)
	{
		
	}
	
	/**
	 * findOrCreateBy
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function findOrCreateBy(array $props)
	{
		
	}
	
	/**
	 * findBy
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function findBy(/* $condition, $options */)
	{
		
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
		if($this->raw){
			$this->Statement->setFetchMode(PDO::FETCH_OBJ);
		}elseif($custom instanceof Closure){
			$custom($this->Statement, $customClass);
		}
		//return raw to it's original state.
		$this->raw(false);
	}
}
