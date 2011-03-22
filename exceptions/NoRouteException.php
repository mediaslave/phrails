<?php
/**
* No route for the expected path.
* @package exceptions
*/
class NoRouteException extends Exception
{
	
	function __construct($message="The route specified does not exist.")
	{
		parent::__construct($message);
	}
}
