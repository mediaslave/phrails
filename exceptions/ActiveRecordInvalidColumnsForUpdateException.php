<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE
 */

/**
* @package exceptions
* @author Justin Palmer
*/
class ActiveRecordInvalidColumnsForUpdateException extends Exception
{

	function __construct($model, $invalid_columns)
	{
		parent::__construct('Active Record invalid columns for update in ' . $model . ': ' . implode(',', $invalid_columns));
	}
}
