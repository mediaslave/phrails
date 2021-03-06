<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
 * @package util
 */
/**
 * class description
 *
 * @package util
 * @author Justin Palmer
 */
class OptionsParser
{
	private static $options;
	/**
	 * Parse and return a string in html attribute format.
	 *
	 * @param string $options
	 * @return string
	 * @author Justin Palmer
	 */
	public static function toHtmlProperties($options, array $optionExceptions=array())
	{
		return self::parse($options, $optionExceptions);
	}
	/**
	 * Parse and return an array of key/value pairs.
	 *
	 * @param string $options
	 * @return array
	 * @author Justin Palmer
	 */
	public static function toArray($options, array $optionExceptions=array())
	{
		return self::parse($options, $optionExceptions, array());
	}
	/**
	 * Parse the options and convert it to the requested.
	 *
	 * @param mixed $options
	 * @param array $optionExceptions - array('remote'=>'data-remote')
	 * @param mixed $return - What are we returning?
	 */
	private static function parse($options, array $optionExceptions=array(), $return='')
	{
		//if it is an array lets do the conversion and return the value.
		if(is_array($options)){
			return self::convertTo($options, $optionExceptions, $return);
		}
		//Do some processing if we actually have some options.
		if($options !== null && $options != ''){
			//All options are comma seperated, make an array out of them.
			$options = explode(',', $options);
			return self::convertTo($options, $optionExceptions, $return);
		}
		return $return;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	private static function convertTo(array $options, array $optionExceptions, $ret)
	{
		$keys = array();
		$do_explode = array_key_exists(0, $options);
		foreach($options as $key => $value){
			//Get the key/value an array
			if($do_explode){
				$option = explode(':', $value);
				if(!isset($option[1])){
					throw new OptionParserParseException($options);
				}
				$key = trim($option[0]);
				$value = trim($option[1]);
			}
			if(!in_array($key, $keys)){
				$keys[] = $key;
			}else{
				continue;
			}
			if(array_key_exists($key, $optionExceptions))
				$key = $optionExceptions[$key];
			//Set the option into the appropriate return type.
			(is_array($ret)) ? $ret[$key] = $value
					 		 				 : $ret .= ' ' . $key . '="' . $value . '"';
		}
		return $ret;
	}

	/**
	 * Turn a string from an array
	 *
	 * Returns a string in format 'key:value,key:value'
	 *
	 * @return string
	 * @author Justin Palmer
	 **/
	public static function toStringFromArray(array $array)
	{
		$string = '';
		foreach($array as $key => $value)
			$string .= "$key:$value,";
		$string = rtrim($string, ',');
		return $string;
	}
	/**
	 * Search for an option and return it if it exists
	 *
	 * @return mixed
	 * @author Justin Palmer
	 **/
	public static function find($key, $options)
	{
		$array = self::toArray($options);
		return (array_key_exists($key, $array)) ? $array[$key]
												: false;
	}
	/**
	 * Destroy an option
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public static function destroy($key, $options)
	{
		$array = self::toArray($options);
		if(array_key_exists($key, $array))
			unset($array[$key]);
		self::$options = $array;
		return self::$options;
	}

	/**
	 * Search and destroy the key and return it if it exists
	 *
	 * @return mixed
	 * @author Justin Palmer
	 **/
	public static function findAndDestroy($key, $options)
	{
		$value = self::find($key, $options);
		self::destroy($key, $options);
		return $value;
	}
	/**
	 * Get the options that are currently available.
	 *
	 * @return mixed
	 * @author Justin Palmer
	 **/
	public static function getOptions()
	{
		return self::$options;
	}
}
