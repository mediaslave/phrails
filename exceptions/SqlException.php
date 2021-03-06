<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
 * @package exceptions
 */

/**
* @package exceptions
* @author Justin Palmer
*/
class SqlException extends Exception
{
	function __construct($message, $code)
	{
		parent::__construct($message, $code);
	}
}
