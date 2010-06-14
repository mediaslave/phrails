<?php
/**
 * Is the current model property an integer.
 *
 * @package html
 * @subpackage rules
 * @author Justin Palmer
 **/
class UriRule extends PregRule
{
	/**
	 * constructor
	 *
	 * @return AlphaExtraRule
	 * @author Justin Palmer
	 **/
	public function __construct($message='%s should be a valid web address.')
	{
		$preg = "/^((http|https|ftp):\/\/(www\.)?|www\.)([a-zA-Z0-9\_\-]+\.)+([a-zA-Z]{2,4}|[a-zA-Z]{2}\.[a-zA-Z]{2})(\/[a-zA-Z0-9\-\._\?\&=,'\+%\$#~]*)*$/";
		parent::__construct($preg, $message);
	}
} // END class Rule