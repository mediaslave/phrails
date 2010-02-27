<?php
/**
* 
*/
class NoActionException extends Exception
{
	
	function __construct()
	{
		parent::__construct("The route specified does not exist.");
	}
}
