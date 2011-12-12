<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
 * @package html
 */
/**
 * class description
 *
 * @package html
 * @author Justin Palmer
 */
class MaleFemaleRadioGroup extends InputRadioGroup
{

	function __construct($name=null, $selectedValue=null, $options=null)
	{
		parent::__construct($name,
						  array(
							array('name' => 'Male', 'id'=>'Male'),
							array('name' => 'Female', 'id'=>'Female')
						  ),
						  $selectedValue,
						  $options);
	}
}
