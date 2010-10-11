<?php
/**
* The record could not be found.
* @package exceptions
*/
class RecordNotFoundException extends Exception
{
	
	function __construct($query, $params)
	{
		$query = preg_replace('/\?/', '%s', $query);
		$query = vsprintf($query, $params);
		parent::__construct("The record could not be found. Query prepared: $query");
	}
}
