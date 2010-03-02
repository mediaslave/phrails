<?php
/**
* 
*/
class NoViewTypeException extends Exception
{
	
	function __construct($types, $requested)
	{
		parent::__construct("No view type is available for the one specified. Valid types: '" . implode(',', array_keys($types->export())) . "', requested type: " . $requested);
	}
}
