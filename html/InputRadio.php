<?php
/**
 * Creates a 'checkbox'.
 * 
 * @author Justin Palmer
 * @package html
 */
class InputRadio extends InputChecked
{	
	protected $options = 'type:radio';
	/**
	 * Constructor
	 *
	 * @param string $display 
	 * @param string or array $options 
	 * @author Justin Palmer
	 */
	function __construct($name, $value, $checked=false, $options=null)
	{
		parent::__construct($name, $value, $checked, $options);
	}
}