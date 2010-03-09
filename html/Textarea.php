<?php
/**
 * Creates a 'label'.
 * 
 * @author Justin Palmer
 * @package html
 */
class Textarea extends Element
{
	/**
	 * Constructor
	 *
	 * @param string $display 
	 * @param string or array $options 
	 * @author Justin Palmer
	 */
	function __construct($name, $value, $options=null)
	{
		$this->options = "name:$name";
		$this->display = $value;
		parent::__construct($options);
	}
	/**
	 * @see Tag::start()
	 */
	public function start()
	{
		return '<textarea' . $this->options . '>';
	}
	/**
	 * @see Tag::end();
	 **/
	public function end(){
		return '</textarea>';
	}
}