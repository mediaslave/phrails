<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
 * @package html
 * @subpackage rules
 */
/**
 * class description
 *
 * @package html
 * @subpackage rules
 * @author Justin Palmer
 */
class UriRule extends PregRule
{
	/**
	 * Constructor
	 *
	 * @return UriRule
	 * @author Justin Palmer
	 **/
	public function __construct($message='%s should be a valid web address.')
	{
		$preg = "/^((http|https|ftp):\/\/(www\.)?|www\.)([a-zA-Z0-9\_\-]+\.)+([a-zA-Z]{2,4}|[a-zA-Z]{2}\.[a-zA-Z]{2})(\/[a-zA-Z0-9\-\._\?\&=,'\+%\$#~]*)*$/";
		parent::__construct($preg, $message);
	}
} // END class Rule
