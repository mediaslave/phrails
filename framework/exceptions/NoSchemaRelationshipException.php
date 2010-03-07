<?php
/**
* The correct order of methods were not called for the schema
* @package exceptions
*/
class NoSchemaRelationshipException extends Exception
{
	
	function __construct($name)
	{
		parent::__construct("You should call 'belongsTo', 'hasMany', 'hasOne' before calling: '$name'().");
	}
}
