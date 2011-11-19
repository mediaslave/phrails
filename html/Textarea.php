<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE
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
		$this->display = $value;
		parent::__construct($name, null, $options);
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
