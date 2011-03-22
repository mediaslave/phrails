<?php
/**
 * Creates a input 'text'.
 * 
 * @author Justin Palmer
 * @package html
 */
class InputText extends Input
{
	protected $options = 'type:text';
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