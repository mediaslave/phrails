<?
/**
* Base datatype.
*/
class TextDataType extends DataType{
	
	
	/**
	 * Run an nl2br on the value
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function toBr()
	{
		return nl2br($this->value());
	}
}
