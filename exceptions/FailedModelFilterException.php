<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE
 * @package exceptions
 */

/**
* @package exceptions
* @author Justin Palmer
*/
class FailedModelFilterException extends Exception
{

	function __construct($class_name, $filter_type, $filter)
	{
		if(is_array($filter)){
			$filter = implode('->', $filter);
		}
		parent::__construct("In object $class_name::$filter_type running $filter failed.");
	}
}
