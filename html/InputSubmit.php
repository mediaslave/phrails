<?php
/**
 * Creates a input 'submit'.
 * 
 * @author Justin Palmer
 * @package html
 */
class InputSubmit extends Input
{
	protected $options = 'type:submit';
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