<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE
 * @package html
 */
/**
 * class description
 *
 * @package html
 * @author Justin Palmer
 */
class InputRadioGroup
{
	private $name, $array, $selectedValue, $options;
	/**
	 * Constructor
	 *
	 * @param string $display
	 * @param array $array - stdClass or comparable
	 * @author Justin Palmer
	 */
	function __construct($name, $array, $selectedValue=null, $options=null)
	{
		$this->name = $name;
		$this->array = $array;
		$this->selectedValue = $selectedValue;
		$this->options = $options;
	}
	/**
	 * To string
	 *
	 * @return string
	 * @author Justin Palmer
	 **/
	public function __toString()
	{
		$html = new InputHidden($this->name, '');
		foreach($this->array as $element){
			if(is_array($element)){
				$element = (object)$element;
			}
			$checked = false;
			if((string)$element->id === (string)$this->selectedValue && $this->selectedValue !== null){
				$checked = true;
			}
			$html .= new InputRadio($this->name, $element->id, $checked, $element->name, $this->options);
		}
		return $html;
	}
}
