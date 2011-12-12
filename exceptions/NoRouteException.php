<?php
/**
 * * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
 * @package exceptions
 */

/**
* @package exceptions
* @author Justin Palmer
*/
class NoRouteException extends Exception
{

	function __construct($message="The route specified does not exist.")
	{
		parent::__construct($message);
	}
}
