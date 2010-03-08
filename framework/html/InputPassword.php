<?php
/**
 * Creates a input 'password'.
 * 
 * @author Justin Palmer
 * @package html
 */
class InputPassword extends Input
{
	protected $options = 'type:password';
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