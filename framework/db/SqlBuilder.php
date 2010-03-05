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
	private $db;
	private $as = '';
	private $select = '*';
	public  $conditions = array();
	private $order = array();
	private $limit = '';
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
	public function __construct($db)
	{
		//print '<br/><br/><br/>';
		//var_dump($db);
		//print '<br/><br/><br/>';
		$this->db = $db;
		$this->NamedScope = new Hash();
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
		//Do we have an AS?
		if($this->as != '')
			$query .= ' AS ' . $this->aas;
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
	 **/
	public function alias($string)
	{
		//print('in alias');
		if($string != '')
			$this->as = $string;
		//print('after set aas');
		//var_dump($this->db);
		//exit();
		return $this->db;
	}
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
		return $this->db;
	}
	/**
	 * Join a table
	 *
	 * @return Adapter
	 * @author Justin Palmer
	 **/
	public function join($from_table, $join_table, $on)
	{
		$this->join[] = array($from_table, $join_table, $on);
		return $this->db;
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
		return $this->db;
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
		return $this->db;
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
		return $this->db;
	}
}