<?php
/**
* The relationship was not specified for the model.
* @package exceptions
*/
class NoSchemaRelationshipDefinedException extends Exception
{
	
	function __construct($table, $name)
	{
		$table = Inflections::classify($table);
		parent::__construct("The relationship: '$name' was not defined for '$table'");
	}
}
