<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
 * @package exceptions
 */

/**
* @package exceptions
* @author Justin Palmer
*/
class UnknownDatabaseTypeException extends Exception
{

	function __construct($type)
	{
		parent::__construct("Database type: `$type` is unknown.");
	}
}
