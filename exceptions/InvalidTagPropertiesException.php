<?php
/**
* No view created for the request.
* @package exceptions
*/
class InvalidTagPropertiesException extends Exception
{

	function __construct($option)
	{
		parent::__construct("Properties passed to a Tag are in the wrong format:<br/><br/> $option  <br/><br/>Please review the var_dump above.  Most likely this is caused by a comma in the key/value pair passed in (Example: 'data-tip:Glass can be red, blue, orange, etc').  To fix this you could use the array syntax for the options (Example: array('data-tip'=>'Glass can be red, blue, orange, etc'");
	}
}
