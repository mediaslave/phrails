<?php
class ResultSet implements IteratorAggregate
{
	private $Statement;
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