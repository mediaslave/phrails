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
		$ret = '{';
		foreach($object as $key => $value){
			$count = 0;
			if($value instanceof ResultSet){
				$ret .= '"' . $key . '":[';
				foreach($value as $record){
					//$count++;
					//$ret .= '"' . $count . '":';
					$ret .= json_encode($record) . ',';
				}	
				$ret = rtrim($ret, ',') . '],';
			}elseif(is_array($value)){
				$ret .= json_encode($value) . ',';
			}else{
				$ret .= trim(json_encode(array($key=>$value)), '{}') . ',';
			}
		}
		return rtrim($ret, ',') . '}';
	}
} // END class Cache