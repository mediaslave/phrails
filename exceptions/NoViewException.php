<?php
/**
* No controller for the specified route.
* @package exceptions
*/
class NoViewException extends Exception
{
	
	function __construct()
	{
		parent::__construct();
	}
}