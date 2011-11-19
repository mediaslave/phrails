<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE
 * @package exceptions
 */

/**
* @package exceptions
* @author Justin Palmer
*/
class NoSchemaRelationshipDefinedException extends Exception
{

	function __construct($table, $name)
	{
		$table = Inflections::classify($table);
		parent::__construct("The relationship: '$name' was not defined for '$table'");
	}
}
