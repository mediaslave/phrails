<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
 * @package util
 */
/**
 * class description
 *
 * @package util
 */
class String
{
	/**
	 * Truncate a string based on the params given.
	 *
	 * Based off Kyle Neath's truncate function.
	 *
	 * @param string $string
	 * @param integer $limit
	 * @param string $pad
	 * @param string $break
	 * @return string
	 * @author Justin Palmer
	 */
	public static function truncate($string, $limit, $pad="...", $break=".") {
	    if(strlen($string) <= $limit) return $string;
	    if(false !== ($breakpoint = strpos($string, $break, $limit))) {
	        if($breakpoint < strlen($string) - 1) {
	            $string = substr($string, 0, $breakpoint) . $pad;
	        }
	    }
	    return $string;
	}

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
