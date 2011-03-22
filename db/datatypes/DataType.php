<?
/**
* Base datatype.
*/
class DataType
{
	protected $value;
	
	public function __construct($value)
	{
		$this->value = $value;
	}
	
	/**
	 * Get the value.
	 *
	 * @return mixed
	 * @author Justin Palmer
	 **/
	public function value()
	{
		return $this->value;
	}
	/**
	 * To string should just return the value
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function __toString()
	{
		return $this->value();
	}
}
