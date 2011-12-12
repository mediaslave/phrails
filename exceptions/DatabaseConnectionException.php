<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
 * @package exceptions
 */

/**
* If the rule does not override the $message var the we will throw an exception.
* @package exceptions
* @author Justin Palmer
*/
class DatabaseConnectionException extends Exception
{

	function __construct($message)
	{
		parent::__construct($message);
	}
}
