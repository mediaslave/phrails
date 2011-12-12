<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
 * @package exceptions
 */

/**
* @package exceptions
* @author Justin Palmer
*/
class NoColumnInTableException extends Exception
{

	function __construct($column, $table)
	{
		parent::__construct("The column: '$column' does not exist in the `" . $table . "` table.");
	}
}
