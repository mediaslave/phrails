<?php
/**
 * Creates a input 'file'.
 * 
 * @author Justin Palmer
 * @package html
 */
class InputFile extends Input
{
	protected $options = 'type:file';
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