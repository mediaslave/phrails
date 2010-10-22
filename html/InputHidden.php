<?php
/**
 * Creates a input 'hidden'.
 * 
 * @author Justin Palmer
 * @package html
 */
class InputHidden extends Input
{
	protected $options = 'type:hidden';
	
	protected $is_hidden = true;
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