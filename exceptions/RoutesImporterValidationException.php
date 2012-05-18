<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
 * @package exceptions
 */

/**
* @package exceptions
* @author Justin Palmer
*/
class RoutesImporterValidationException extends Exception
{

	function __construct($message)
	{
		parent::__construct($message);
	}
}
