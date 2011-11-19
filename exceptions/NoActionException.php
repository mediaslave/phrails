<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE
 */

/**
* @package exceptions
* @author Justin Palmer
*/
class NoActionException extends Exception
{

	function __construct()
	{
		parent::__construct("The route specified does not exist.");
	}
}
