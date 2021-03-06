<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
 * @package template
 */
/**
 * class description
 *
 * @package template
 */
class Json
{
	/**
	 *
	 * Decode a json string
	 *
	 * @return array
	 * @author Justin Palmer
	 **/
	public static function decode($string)
	{
		return json_decode($string);
	}
	/**
	 * Encode the data
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public static function encode($object, $callBack=null)
	{
		if($object instanceof stdClass){
			return self::encodeStdClass($object);
		}
		//encode an array
		if(is_array($object)){
			$ret = '[';
			foreach($object as $value){
				//new Dbug($value, '', false, __FILE__, __LINE__);
				if($value instanceof Model){
					$ret .= json_encode($value->props()->export()) . ',';
				}elseif($value instanceof stdClass){
					$ret .= self::encodeStdClass($value) . ',';
				}else{
					return json_encode($object);
				}
			}
			return rtrim($ret, ',') . ']';
		}else{
			return $object;
		}
	}

	/**
	 * Encode a stdClass
	 *
	 * @return string
	 * @author Justin Palmer
	 **/
	public static function encodeStdClass($object)
	{
		$ret = '{';
		foreach($object as $key => $value){
			$ret .= '"' . $key . '":';
			if($value instanceof stdClass){
				$ret .= json_encode($value) . ',';
				continue;
			}
			if(is_array($value)){
				$ret .= '[';
				foreach(array_values($value) as $ivalue){
					if($ivalue instanceof Model){
						$ret .= json_encode($ivalue->props()->export()) . ',';
					}elseif($ivalue instanceof stdClass){
						$ret .= json_encode((array) $ivalue) . ',';
					}else{
						$ret .= json_encode($ivalue) . ',';
					}
				}
				$ret = rtrim($ret, ',') . '],';
			}else{
				$ret .= '"' . $value . '",';
			}
		}

		return rtrim($ret, ',') . '}';
	}
}
