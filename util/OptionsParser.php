<?php
/**
 * Parses the options string that are sent in.
 *
 * @package util
 * @author Justin Palmer
 */
class OptionsParser
{
	/**
	 * Parse and return a string in html attribute format.
	 *
	 * @param string $options 
	 * @return string
	 * @author Justin Palmer
	 */
	public static function toString($options)
	{
		return self::parse($options);
	}
	/**
	 * Parse and return an array of key/value pairs.
	 *
	 * @param string $options 
	 * @return array
	 * @author Justin Palmer
	 */
	public static function toArray($options)
	{
		return self::parse($options, true);
	}
	/**
	 * @nodoc
	 */
	private function parse($options, $array=false)
	{	
		//var_dump($options);
		//Initialize return var
		($array) ? $ret = array() : $ret = '';
		//Do some processing if we actually have some options.
		if($options !== null && $options != ''){
			//All options are comma seperated, make an array out of them.
			$options = explode(',', $options);
			foreach($options as $value){
				//Get the key/value an array
				$option = explode(':', $value);
				$key = trim($option[0]);
				$value = trim($option[1]);
				//Set the option into the appropriate return type.
				($array) ? $ret[$key] = $value
						 : $ret .= ' ' . $key . '="' . $value . '"';
			}
		}
		return $ret;
	}
}
