<?php
/**
 * * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
 * @package html
 */
/**
 * class description
 *
 * @package html
 * @author Justin Palmer
 */
class InputCheckbox extends InputChecked
{
	protected $options = 'type:checkbox';
	/**
	 * Constructor
	 *
	 * @param string $display
	 * @param string or array $options
	 * @author Justin Palmer
	 */
	function __construct($name, $options=null, $checked_value=1, $unchecked_value=0)
	{
		$this->Hidden = new InputHidden($name, $unchecked_value, $options);
		parent::__construct($name, $checked_value, $options);
	}

	/**
	 * Override the default start tag of input to deal with checked.
	 *
	 * @see Tag::start()
	 **/
	public function start()
	{
		return $this->Hidden . "\n" . parent::start();
	}
}
