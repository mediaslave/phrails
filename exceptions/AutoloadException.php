<?php
/**
 * * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
 * @package exceptions
 */

/**
* @package exceptions
* @author Justin Palmer
*/
class AutoloadException extends Exception
{

	function __construct($class_name)
	{
		parent::__construct('The object `' . $class_name . '` could not be loaded with the autoload.');
	}
}
