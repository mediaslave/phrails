<?php
/**
 * String class
 *
 * @package util
 * @author Justin Palmer
 **/
class String
{
	/**
	 * Escape the passed in var.
	 *
	 * @param string $value 
	 * @return string
	 * @author Justin Palmer
	 */
	public static function escape($value)
	{
		if(is_string($value))
			$value =  htmlentities($value);
		return $value;
	}
	/**
	 * Decode the passed in var.
	 *
	 * @param string $value 
	 * @return string
	 * @author Justin Palmer
	 */
	public static function decode($value){
		if(is_string($value))
			$value =  html_entity_decode($value);
		return $value;
	}
} // END class String