<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
 * @package exceptions
 */

/**
* @package exceptions
* @author Justin Palmer
*/
class NoSchemaRelationshipException extends Exception
{

	function __construct($name)
	{
		parent::__construct("You should call 'belongsTo', 'hasMany', 'hasOne' before calling: '$name'().");
	}
}
