<?php
/**
 * Creates a 'label'.
 * 
 * @author Justin Palmer
 * @package html
 */
class Input extends FormElement
{
	protected $hasEndTag = false;

	/**
	 * @see Tag::start()
	 */
	public function start()
	{
		return '<input' . $this->options . ' />';
	}
	/**
	 * @see Tag::end();
	 **/
	public function end(){}
}