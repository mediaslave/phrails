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
class HonorificSelect extends Select
{

	function __construct($name=null, $selectedValue=null, $options=null)
	{
		parent::__construct($name, $selectedValue,
						  new Option('Mr.'),
						  new Option('Mrs.'),
						  new Option('Ms.'),
						  new Option('Dr.'),
						  new Option('Prof.'),
						  $options);
	}
}
