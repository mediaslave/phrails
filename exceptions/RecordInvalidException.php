<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
 * @package exceptions
 */

/**
* @package exceptions
* @author Justin Palmer
*/
class RecordInvalidException extends Exception
{

	function __construct()
	{
		parent::__construct("The model did not validate.");
	}
}
