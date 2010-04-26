<?php
/**
 * Creates a 'label'.
 * 
 * @author Justin Palmer
 * @package html
 */
class Div extends Tag
{
	/**
	 * Constructor
	 *
	 * @param string $display 
	 * @param string or array $options 
	 * @author Justin Palmer
	 */
	function __construct($display, $options=null)
	{
		parent::__construct($options);
		$this->display = $display;
	}
	/**
	 * @see Tag::start()
	 */
	public function start()
	{
		return '<div' . $this->options . '>';
	}
	/**
	 * @see Tag::end()
	 */
	public function end()
	{
		return '</div>';
	}
}