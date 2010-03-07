<?php
/**
* The route specified does not exist.
* @package exceptions
*/
class NoActionException extends Exception
{
	
	function __construct()
	{
		parent::__construct("The route specified does not exist.");
	}
}
