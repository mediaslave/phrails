<?php
/**
 * Creates a 'label'.
 * 
 * @author Justin Palmer
 * @package html
 */
class Option extends Element
{
	public $selected;
	public $value;
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
		$this->options = "value:$value";
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
			$selected = ' selected';
		return '<option' . $this->options . "$selected>";
	}
	/**
	 * @see Tag::end()
	 */
	public function end()
	{
		return '</option>';
	}
}