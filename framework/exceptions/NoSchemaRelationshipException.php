<?php
/**
* 
*/
class NoSchemaRelationshipException extends Exception
{
	
	function __construct($name)
	{
		parent::__construct("You should call 'belongsTo', 'hasMany', 'hasOne' before calling: '$name'().");
	}
}
