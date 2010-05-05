<?php
/**
 * Return an iterator if there is more than one row
 * Return an stdClass if there is 1
 * throw an RecordNotFoundException not found if there is 0
 *
 * @package db
 * @author Justin Palmer
 **/
class ResultFactory
{
	/**
	 * Return the correct object back.
	 *
	 * @param PDOStatement $Statement
	 * @return mixed
	 * @author Justin Palmer
	 **/
	public static function factory(PDOStatement $Statement, $set = false)
	{
		$count = $Statement->rowCount();
		if($set || $count > 1)
			return new ResultSet($Statement);
		switch($count){
			case 0:
				throw new RecordNotFoundException($Statement->queryString);
			case 1:
				return $Statement->fetchObject('ResultRow');
		}
	}
} // END class DatabaseResultFactory

