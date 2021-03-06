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
class Input extends FormElement
{
	protected $hasEndTag = false;

	/**
	 * @see Tag::start()
	 */
	public function start()
	{
		return '<input' . $this->options . ' value="' . $this->value . '" />';
	}
	/**
	 * @see Tag::end();
	 **/
	public function end(){}
}
