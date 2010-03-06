<?php
/**
* 
*/
class RecordInvalidException extends Exception
{
	
	function __construct()
	{
		parent::__construct("The model did not validate.");
	}
}
