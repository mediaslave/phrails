<?php
/**
* No view created for the request.
* @package exceptions
*/
class OptionParserParseException extends Exception
{

	function __construct($option)
	{
		parent::__construct("The OptionParser could not parse the given options. It could be that you have a comma (,) in one of your option values.  If this is the case, try using the array option syntax.  Example: array('property-name' => 'property-value')");
	}
}
