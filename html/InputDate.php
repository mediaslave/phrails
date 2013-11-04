<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
 * @package html
 */
/**
 * class description
 *
 * @package html
 */
class InputDate extends FormElement
{
	protected $hasEndTag = false;

	/**
	 * @see Tag::start()
	 */
	public function start()
	{
		return '<input' . $this->options . ' value="' . $this->value . '" data-date="true"/>';
	}
	/**
	 * @see Tag::end();
	 **/
	public function end(){}
}
