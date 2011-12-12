<?php
/**
 * * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
 * @package db
 * @subpackage datatypes
 */

/**
* @package db
* @subpackage datatypes
* @author Justin Palmer
*/
class DatetimeDataType extends DataType{

	/**
	 * Format a date into the format specified
	 *
	 * Follows the formatting of the PHP <code>DateTime</code> class
	 *
	 * @return mixed
	 * @author Justin Palmer
	 **/
	public function format($format = 'Y-m-d H:i:s')
	{
		$o = new DateTime($this->value());
		return $o->format($format);
	}
	/**
	 *
	 * Calculate the difference from the objectify date to the passed in date
	 *
	 * @return string
	 * @author Justin Palmer
	 **/
	public function diff($date, $format="%d")
	{
		$obj = new DateTime($this->value());
		$date = new DateTime($date);
		$interval = $obj->diff($date);
		return $interval->format($format);
	}

	/**
	 * Calculate the age between two dates
	 *
	 * Format is specified by the PHP <code>DateInterval::format</code> method.
	 *
	 * @return string
	 * @author Justin Palmer
	 **/
	public function age($format="%y")
	{
		$now = new DateTime();
		$birth = new Datetime($this->value());
		$interval = $birth->diff($now);
		return $interval->format($format);
	}
	/**
	 * Get the year
	 *
	 * @return integer
	 * @author Justin Palmer
	 **/
	public function year($format = '%y')
	{
		return $this->format($format);
	}
	/**
	 * Get the month
	 *
	 * @return integer
	 * @author Justin Palmer
	 **/
	public function month($format='%m')
	{
		return $this->format($format);
	}
	/**
	 * Get the integer
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function day($format='%d')
	{
		return $this->format($format);
	}
	/**
	 *
	 * Add via DateInterval
	 *
	 * @return string
	 * @author Justin Palmer
	 **/
	public function datetime()
	{
		return new DateTime($this->value());
	}
}
