<?php
/**
 * Creates a 'label'.
 * 
 * @author Justin Palmer
 * @package html
 */
class Label extends Tag
{
	/**
	 * Constructor
	 *
	 * @param string $display 
	 * @param string or array $options 
	 * @author Justin Palmer
	 */
	function __construct($display, $for, $options=null)
	{
		$this->options = "for:$for";
		parent::__construct($options);
		$this->display = $display;
	}
	/**
	 * @see Tag::start()
	 */
	public function start()
	{
		return '<label' . $this->options . '>';
	}
	/**
	 * @see Tag::end()
	 */
	public function end()
	{
		return '</label>';
	}
}