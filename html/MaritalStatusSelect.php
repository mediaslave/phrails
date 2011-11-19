<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE
 */
/**
 * class description
 *
 * @package html
 * @author Justin Palmer
 */
class MaritalStatusSelect extends Select
{

	function __construct($name=null, $selectedValue=null, $options=null)
	{
		parent::__construct($name, $selectedValue,
												new Option('Married'),
												new Option('Single'),
												new Option('Widowed'),
												new Option('Divorced'),
												$options);
	}
}
