<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE
 */
/**
 * Creates a link.
 *
 * @author Justin Palmer
 * @package html
 */
class A extends Tag
{
	/**
	 * Constructor
	 *
	 * @param string $display
	 * @param string $path
	 * @param string or array $options
	 * @author Justin Palmer
	 */
	function __construct($display, $path, $options=null)
	{
		parent::__construct($options, array('method'=>'data-method', 'remote'=>'data-remote', 'confirm'=>'data-confirm'));
		$this->display = $display;
		$this->href = $path;
	}
	/**
	 * @see Tag::start()
	 */
	public function start()
	{
		return '<a href="' . $this->href . '"' . $this->options . '>';
	}
	/**
	 * @see Tag::end()
	 */
	public function end()
	{
		return '</a>';
	}
}
