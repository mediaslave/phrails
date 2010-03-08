<?php
/**
 * Creates a input 'reset'.
 * 
 * @author Justin Palmer
 * @package html
 */
class InputReset extends Input
{
	protected $options = 'type:reset';
	/**
	 * Constructor
	 *
	 * @param string $display 
	 * @param string or array $options 
	 * @author Justin Palmer
	 */
	function __construct($name, $value, $options=null)
	{
		parent::__construct($name, $value, $options);
	}
}