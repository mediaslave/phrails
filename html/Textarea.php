<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
 * @package html
 */
/**
 * class description
 *
 * @package html
 * @author Justin Palmer
 */
class Textarea extends FormElement
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
		parent::__construct($name, null, $options);
	}
	/**
	 * @see Tag::start()
	 */
	public function start()
	{
		return '<textarea' . $this->options . '>' . $this->value;
	}
	/**
	 * @see Tag::end();
	 **/
	public function end(){
		return '</textarea>';
	}
}
