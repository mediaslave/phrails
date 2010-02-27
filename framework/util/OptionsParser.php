<?php
/**
* 
*/
class OptionsParser
{
	public static function toString($options)
	{
		return self::parse($options);
	}
	public static function toArray($options)
	{
		return self::parse($options, true);
	}
	private function parse($options, $array=false)
	{	
		($array) ? $ret = array() : $ret = '';
		$options = explode(',', $options);
		foreach($options as $value){
			$option = explode(':', $value);
			$key = trim($option[0]);
			$value = trim($option[1]);
			($array) ? $ret[$key] = $value
					 : $ret .= ' ' . $key . '="' . $value . '"';
		}
		return $ret;
	}
}
