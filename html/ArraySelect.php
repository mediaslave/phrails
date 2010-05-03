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
		$options = $this->preparePrompt($options);
		foreach($array as $option)
			$this->display .= $option;
		parent::__construct($name, $selectedValue, null, $options);
	}
}