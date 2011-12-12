<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
 * @package exceptions
 */

/**
* @package exceptions
* @author Justin Palmer
*/
class RecordNotFoundException extends Exception
{

	function __construct($query, $params)
	{
		$query = preg_replace('/\?/', '%s', $query);
		$query = vsprintf($query, $params);
		parent::__construct("The record could not be found. Query prepared: $query");
	}
}
