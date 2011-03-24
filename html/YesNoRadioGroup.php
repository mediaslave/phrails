<?php
/**
* 
*/
class YesNoRadioGroup extends InputRadioGroup
{
	
	function __construct($name=null, $selectedValue=null, $options=null)
	{
		parent::__construct($name, 
						  array(
							array('name' => 'Yes', 'id'=>1),
							array('name' => 'No', 'id'=>0)
						  ),
						  $selectedValue,
						  $options);
	}
}
