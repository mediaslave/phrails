<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE
 * @package exceptions
 */

/**
* @package exceptions
* @author Justin Palmer
*/
class NoViewTypeException extends Exception
{

	function __construct($types, $requested)
	{
		parent::__construct("No view type is available for the one specified. Valid types: '" . implode(',', array_keys($types->export())) . "', requested type: " . $requested);
	}
}
