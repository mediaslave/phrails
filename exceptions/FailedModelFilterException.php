<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
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
    if($filter instanceof Closure) {
      $filter = "Closure";
    }
		parent::__construct("In object $class_name::$filter_type running $filter failed.");
	}
}
