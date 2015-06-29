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
class YesNoEquivalentRadioGroup extends InputRadioGroup
{

	function __construct($name=null, $selectedValue, $options=null)
	{
		if($selectedValue === null){
			$selectedValue = OptionsParser::findAndDestroy('default', $options);
		}
		parent::__construct($name,
						  array(
							array('name' => 'Yes', 'id'=>'Y'),
							array('name' => 'No', 'id'=>'N'),
                            array('name' => 'No, but Equivalent', 'id'=>'E')
						  ),
						  $selectedValue,
						  $options);
	}
}
