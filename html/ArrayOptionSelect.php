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
class ArrayOptionSelect extends Select
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
