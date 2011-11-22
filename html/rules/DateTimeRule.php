<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE
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
class DateTimeRule extends PregRule
{
	/**
	 * constructor
	 *
	 * @return DateTimeRule
	 * @author Justin Palmer
	 **/
	public function __construct($message='%s should be a date in format: YYYY-MM-DD HH:MM:SS')
	{
		parent::__construct("/^(19|20)[0-9]{2}-[0|1][0-9]-[0-3][0-9]\s\d{2}:\d{2}(:\d{2})?$/", $message);
	}
	/**
	 * Run the rule
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function run()
	{
		if($this->value === null)
			return true;
		return parent::run();
	}
} // END class Rule
