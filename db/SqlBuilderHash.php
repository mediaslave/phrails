<?php
/**
 * Build sql statements
 *
 * @todo named_scope
 * 
 * @package db
 * @author Justin Palmer
 */
class SqlBuilderHash extends Hash
{
	function __construct() {
		parent::__construct();
		$this->select('*');
		$this->whereArgs(array());
		$this->doGetSet('count', array());
	}

	/**
	 * Set the var or return the value if the value passed in is null
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	private function doGetSet($key, $value)
	{
		if($value === null){
			return $this->get($key);
		}
		$this->set($key, $value);
	}	
	
	/**
	 * override Hash::get
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function get($key)
	{
		return (parent::get($key) === null) ? '' : parent::get($key);
	}
	/**
	 * select text snippet
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function select($value=null)
	{
		return $this->doGetSet('select', $value);
	}
	
	/**
	 * table
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function from($value=null)
	{
		return $this->doGetSet('from', $value);
	}
	
	/**
	 * join
	 *
	 * @return mixed
	 * @author Justin Palmer
	 **/
	public function join($value=null)
	{
		return $this->doGetSet('join', $value);
	}
	
	/**
	 * where
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function where($value=null)
	{
		return $this->doGetSet('where', $value);		
	}
	
	/**
	 * Get the where_args
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function whereArgs($value=null)
	{
		$array = array();
		if(is_array($value)){
			foreach(array_values($value) as $param){
				if(is_array($param)){
					$array = array_merge($array, $param);
					continue;
				}
				$array[] = $param;
			}
			$value = $array;
		}
		return $this->doGetSet('where_args', $value);
	}
	
	/**
	 * order
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function order($value=null)
	{
		return $this->doGetSet('order', $value);
	}
	
	/**
	 * @return void
	 * @author Justin Palmer
	 **/
	public function limit($value=null)
	{
		return $this->doGetSet('limit', $value);
	}
	/**
	 * @return void
	 * @author Justin Palmer
	 **/
	public function offset($value=null)
	{
		return $this->doGetSet('offset', $value);
	}
	
	/**
	 * group
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function group($value=null)
	{
		return $this->doGetSet('group', $value);
	}
	
	/**
	 * having
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function having($value=null)
	{
		return $this->doGetSet('having', $value);
	}
	
	/**
	 * count
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function count($column=null, $as=null, $distinct=null)
	{
		if($column === null){
			return $this->get('count');
		}
		$array = $this->get('count');
		$o = new stdClass;
		$o->column = $column;
		$o->as = $as;
		$o->distinct = $distinct;
		$array[] = $o;
		$this->set('count', $array);
	}
	
	/**
	 * props of a model
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function props($value=null)
	{
		return $this->doGetSet('props', $value);
	}
}