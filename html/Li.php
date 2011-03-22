<?php
/**
 * Creates a 'li'.
 * 
 * @author Justin Palmer
 * @package html
 */
class Li extends Tag
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
		return '<li' . $this->options . '>';
	}
	/**
	 * @see Tag::end()
	 */
	public function end()
	{
		return '</li>';
	}
}