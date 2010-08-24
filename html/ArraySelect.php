<?php
/**
 * Creates a 'label'.
 * 
 * @author Justin Palmer
 * @package html
 */
class ArraySelect extends Select
{
	/**
	 * Constructor
	 *
	 * @param string $name
	 * @param string $selectedValue 
	 * @param Option $optionsTags 
	 * @param string or array $options 
	 * @author Justin Palmer
	 */
	function __construct($name, array $array, $selectedValue=null, $options=null)
	{
		parent::__construct($name, $selectedValue, $array, $options);
	}
}