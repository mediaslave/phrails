<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
 * @package exceptions
 */

/**
* @package exceptions
* @author Justin Palmer
*/
class UnknownActiveRecordDynamicFinderException extends Exception
{

	function __construct($method)
	{
		parent::__construct("The dynamic finder: `$method` could not be found");
	}
}
