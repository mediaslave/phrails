<?php
/**
* No route for the expected path.
* @package exceptions
*/
class NoRouteException extends Exception
{
	
	function __construct()
	{
		parent::__construct("The route specified does not exist.");
	}
}
