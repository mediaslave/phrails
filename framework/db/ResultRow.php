<?php
class ResultRow
{
	/**
	 * Return 1 for count
	 *
	 * @return integer
	 * @author Justin Palmer
	 **/
	public function count()
	{
		return 1;
	}
	/**
	 * Set the vars escaped
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function __set($key, $value)
	{
		$value = String::escape($value);
		$this->$key = $value;
	}
	/**
	 * We have to have a constructor, because of setFetchMode
	 *
	 * We set this in ResultSet, so that each iteration can return a ResultRow.
	 * 
	 * @see ResultSet
	 * @return ResultRow
	 * @author Justin Palmer
	 **/
	public function __construct(){}
}