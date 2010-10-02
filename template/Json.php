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
	public static function encode($object, $callBack=null)
	{
		if($object instanceof ResultSet)
			return self::encodeResultSet(null, $object, true, $callBack);
		$ret = '{';
		foreach($object as $key => $value){
			if($value instanceof ResultRow){
				$ret .= self::encodeResultRow($key, $value);
			}elseif($value instanceof ResultSet){
				$ret .= self::encodeResultSet($key, $value, false, $callBack);
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
	private static function encodeResultSet($key, $object, $only=false, $callBack=null)
	{
		$hasCallback = false;
		if($callBack !== null &&
		  is_array($callBack) && 
		  count($callBack) == 2){
			$obj = array_shift($callBack);
			$method = array_shift($callBack);
			$obj = new $obj();
			$hasCallback = true;
		}
		$ret = '"' . $key . '":[';
		if($only)
			$ret = '[';
		foreach($object as $record){
			if($hasCallback)
				$record = $obj->$method($record);
			$ret .= json_encode($record) . ',';
		}	
		$ret = rtrim($ret, ',') . ']';
		return ($only) ? $ret : $ret .= ',';
	}
	
	/**
	 * Encode a result row.
	 *
	 * @return string
	 * @author Justin Palmer
	 **/
	private static function encodeResultRow($key, ResultRow $row)
	{
		$current = '';
		$ret = '"' . $key . '":{';
		foreach($row as $key=>$value){
			if($value instanceof ResultSet){
				$current = self::encodeResultSet($key, $value);
			}else{
				$current = '"' . $key . '":"' . $value . '",';
			}
			$ret .= $current;
		}
		return rtrim($ret, ',') . '},';
	}
}