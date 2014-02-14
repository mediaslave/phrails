<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
 * @package html
 */
/**
 * class description
 *
 * @package html
 */
class ArrayMultiSelect extends MultiSelect
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
	function __construct($name, $array, $selectedValue, $options=null)
	{
		$args = func_get_args();
		$name = array_shift($args);
		$array = array_shift($args);
		$options = array_pop($args);
		$args = array_shift($args);
		parent::__construct($name, $args, $array, $options);
	}
}
