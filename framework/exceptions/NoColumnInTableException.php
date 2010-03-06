<?php
/**
* 
*/
class NoColumnInTableException extends Exception
{
	
	function __construct($column, $table)
	{
		parent::__construct("The column: '$column' does not exist in the `" . $table . "` table.  You may not add a rule to a column that does not exist.");
	}
}
