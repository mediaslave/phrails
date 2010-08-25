<?php
/**
 * Json
 *
 * @package template
 * @author Justin Palmer
 **/
class Json
{
	/**
	 * Encode the data
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public static function encode($object)
	{
		if($object instanceof ResultSet)
			return self::encodeResultSet(null, $object, true);
		if($object instanceof Model){
			return json_encode($object->props()->export());
		}
		$ret = '{';
		foreach($object as $key => $value){
			$count = 0;
			if($value instanceof ResultSet){
				//new Dbug($key, '', false, __FILE__, __LINE__);
				$ret .= self::encodeResultSet($key, $value);
			}elseif(is_array($value)){
				$ret .= json_encode($value) . ',';
			}else{
				$ret .= trim(json_encode(array($key=>$value)), '{}') . ',';
			}
		}
		return rtrim($ret, ',') . '}';
	}
	
	/**
	 * Encode the result set
	 *
	 * @return string
	 * @author Justin Palmer
	 **/
	private static function encodeResultSet($key, $object, $only=false)
	{
		$ret = '"' . $key . '":[';
		if($only)
			$ret = '[';
		foreach($object as $record){
			//$count++;
			//$ret .= '"' . $count . '":';
			$ret .= json_encode($record) . ',';
		}	
		$ret = rtrim($ret, ',') . ']';
		return ($only) ? $ret : $ret .= ',';
	}
} // END class Cache