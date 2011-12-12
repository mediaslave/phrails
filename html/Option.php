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
class Option extends Element
{
	public $selected;
	public $value;
	public $display;
	/**
	 * Constructor
	 *
	 * @param string $display
	 * @param string $value
	 * @param string or array $options
	 * @author Justin Palmer
	 */
	function __construct($display, $value=null, $selected=false, $options=null)
	{
		$this->display = $display;
		if($value === null)
			$value = $display;
		$this->selected = $selected;
		$this->value = $value;
		parent::__construct($options);
	}
	/**
	 * @see Tag::start()
	 */
	public function start()
	{
		$selected = '';
		if($this->selected == true)
			$selected = ' selected="selected"';
		return '<option value="' . $this->value . '" ' . $this->options . "$selected>";
	}
	/**
	 * @see Tag::end()
	 */
	public function end()
	{
		return '</option>';
	}
}
