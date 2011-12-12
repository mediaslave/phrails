<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
 * @package db
 */

/**
* @package db
* @author Justin Palmer
*/
class DataTypeFactory
{
	/**
	 * Factory method
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	static public function process($datatype, $value)
	{

		try{
			$arr = explode('(', $datatype);
			$datatype = ucfirst(array_shift($arr)) . 'DataType';
			return new $datatype($value);
		}catch(AutoloadException $e){
			return new DataType($value);
		}
	}
}
