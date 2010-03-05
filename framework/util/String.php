<?php
/**
 * String class
 *
 * @package util
 * @author Justin Palmer
 **/
class String
{
	public static function escape($value)
	{
		if(is_string($value))
			$value =  htmlentities($value);
		return $value;
	}
	
	public static function decode($value){
		if(is_string($value))
			$value =  html_entity_decode($value);
		return $value;
	}
} // END class String