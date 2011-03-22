<?

/**
* Datatype factory attempts to get an object that represents the 
* datatype that a specific model column is.		
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
