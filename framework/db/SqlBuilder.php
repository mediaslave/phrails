<?php
/**
 * Build sql statements
 *
 * @todo named_scope
 * @package db
 * @author Justin Palmer
 */
class SqlBuilder
{
	private $model;
	private $as;
	private $select;
	public  $conditions;
	private $order;
	private $limit;
	private $relationships;
	/**
	 * what to do this this?
	 */
	private $NamedScope;
	/**
	 * Constructor
	 *
	 * @return SqlBuilder
	 * @author Justin Palmer
	 **/
	public function __construct($model)
	{
		//print '<br/><br/><br/>';
		//var_dump($db);
		//print '<br/><br/><br/>';
		$this->model = $model;
		$this->NamedScope = new Hash();
		//Resets all class level vars to their default states.
		$this->reset();
	}
	/**
	 * build the additional items to the query
	 *
	 * @return stdClass
	 * @author Justin Palmer
	 **/
	public function build($query)
	{
		//print 'build';
		
		//Add the select
		$query = str_replace('?', $this->select, $query);
		
		$query .= ' AS ' . $this->model->alias();
			
		//build all of the joins that are called by join()
		$query .= $this->buildAllJoins();
		//class to hold the query and the params to return.
		$result = new stdClass;
		//any conditions?
		if(!empty($this->conditions)){
			$query .= ' WHERE ' . array_shift($this->conditions);
		}
		//any order?
		if(!empty($this->order)){
			$query .= ' ORDER BY ' . array_shift($this->order);
		}
		//any limit?
		if($this->limit != ''){
			$query .= ' LIMIT ' . $this->limit;
		}
		$result->params = array_merge($this->conditions, $this->order);
		$result->query = $query;
		//print $query;
		return $result;
	}
	/**
	 * Alias the main table
	 *
	 * @return Adapter
	 * @author Justin Palmer
	 
	public function alias($string)
	{
		//print('in alias');
		if($string != '')
			$this->as = $string;
		//print('after set aas');
		//var_dump($this->model);
		//exit();
		return $this->model;
	}**/
	/**
	 * Add items to the select
	 *
	 * @return Adapter
	 * @author Justin Palmer
	 **/
	public function select($string)
	{
		if($string != '')
			$this->select = $string;
		return $this->model;
	}
	
	/**
	 * Join the tables passed in based off the Schema.
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function join($args)
	{
		$args = func_get_args();
		foreach($args as $key){
			if(!$this->model->schema()->relationships->isKey($key))
				throw new NoSchemaRelationshipDefinedException($this->model->table_name(), $key);
			$this->relationships[] = $this->model->schema()->relationships->get($key);
		}
		return $this->model;
	}
	/**
	 * Build out the join
	 *
	 * @return string
	 * @author Justin Palmer
	 **/
	private function buildAllJoins()
	{
		$joins = '';
		if(!empty($this->relationships)){
			foreach($this->relationships as $key => $join){
				var_dump($join);
				$joins .= " INNER JOIN `" . $join->table . "` AS " . $join->alias . 
						  " ON " . $join->on . " ";
			}
		}
		return $joins;
	}
	/**
	 * Add the conditions
	 *
	 * @return Adapter
	 * @author Justin Palmer
	 **/
	public function conditions($conditions)
	{
		$this->conditions = func_get_args();
		return $this->model;
	}	
	/**
	* Add the order
	*
	* @return Adapter
	* @author Justin Palmer
	**/
	public function order($order)
	{
		$this->order = func_get_args();
		return $this->model;
	}	
	/**
	 * Add the limit
	 *
	 * @return Adapter
	 * @author Justin Palmer
	 **/
	public function limit($min, $max=null)
	{
		$this->limit = $min;
		if($max !== null)
			$this->limit = $min . ',' . $max;
		return $this->model;
	}
	/**
	 * Reset the conditions.
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function reset()
	{
		$this->as = '';
		$this->select = '*';
		$this->conditions = array();
		$this->order = array();
		$this->limit = '';
		$this->relationships = array();
	}
}