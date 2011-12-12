<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
 * @package exceptions
 */

/**
* @package exceptions
* @author Justin Palmer
*/
class OptionParserParseException extends Exception
{

	function __construct($option)
	{
		parent::__construct("The OptionParser could not parse the given options. It could be that you have a comma (,) in one of your option values.  If this is the case, try using the array option syntax.  Example: array('property-name' => 'property-value')");
	}
}
