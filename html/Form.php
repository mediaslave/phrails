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
class Form extends Element
{
	protected $hasEndTag = false;
	/**
	 * Constructor
	 *
	 * @param string $display
	 * @param string or array $options
	 * @author Justin Palmer
	 */
	function __construct($action, $options=null)
	{
		$this->options .= "action:$action";
		parent::__construct($options, array('remote'=>'data-remote'));
	}
	/**
	 * @see Tag::start()
	 */
	public function start()
	{
		return '<form' . $this->options . '>';
	}
	/**
	 * @see Tag::end()
	 */
	public function end()
	{
		return '</form>';
	}
}
