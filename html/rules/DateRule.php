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
 */
class DateRule extends PregRule
{
	/**
	 * constructor
	 *
	 * @return NameRule
	 * @author Justin Palmer
	 **/
	public function __construct($message='%s should be a date in format: YYYY-MM-DD')
	{
		parent::__construct("/^(19|20)[0-9]{2}-[0|1][0-9]-[0-3][0-9]$/", $message);
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
		$boolean = parent::run();

		if($boolean){
			//Split the date into an array via dashes.
			$date = preg_split('/\-/', $this->value);
			//use the php checkdate function to see if the date is valid.
			if(!checkdate($date['1'], $date['2'], $date['0'])) {
				$boolean = FALSE;
			}
		}

		return $boolean;
	}
} // END class Rule
