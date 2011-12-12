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
class DataType
{
	protected $value;

	public function __construct($value)
	{
		$this->value = $value;
	}

	/**
	 * Get the value.
	 *
	 * @return mixed
	 * @author Justin Palmer
	 **/
	public function value()
	{
		return $this->value;
	}
	/**
	 * To string should just return the value
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function __toString()
	{
		return $this->value();
	}
}
