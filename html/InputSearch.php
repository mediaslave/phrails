<?php
/**
 * Creates a input 'text'.
 * 
 * @author Justin Palmer
 * @package html
 */
class InputSearch extends Input
{
	protected $options = 'type:search';
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