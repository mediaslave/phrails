<?php
/**
 * Represents many results
 *
 * @package db
 * @author Justin Palmer
 */
class ResultSet implements IteratorAggregate
{
	/**
	 * PDO::Statement
	 *
	 * @var PDOStatement
	 */
	private $Statement;
	/**
	 * Constructor
	 *
	 * @param PDOStatement $stmt 
	 * @author Justin Palmer
	 */
	function __construct(PDOStatement $stmt)
	{
		$this->Statement = $stmt;
		$this->Statement->setFetchMode(PDO::FETCH_CLASS, 'ResultRow');
	}
	
	/**
	 * The num records
	 *
	 * @return integer
	 * @author Justin Palmer
	 **/
	public function count()
	{
		return $this->Statement->rowCount();
	}
	/**
	 * Get the iterator
	 *
	 * @return IteratorIterator
	 * @author Justin Palmer
	 **/
	public function getIterator()
	{
		return new IteratorIterator($this->Statement);
	}
}