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
class InputRadioGroup
{
	private $name, $array, $selectedValue, $options;

	private static $hidden_sent = false;
	/**
	 * Constructor
	 *
	 * @param string $display
	 * @param array $array - stdClass or comparable
	 */
	function __construct($name, $array, $selectedValue=null, $options=null)
	{
		$this->name = $name;
		$this->array = $array;
		$this->selectedValue = $selectedValue;
		$value = OptionsParser::findAndDestroy('value', $options);
		if($value !== false){
			$this->selectedValue = $value;
		}
		$this->options = $options;
	}

	/**
	 * Set the selected value
	 * 
	 * @return void
	 */
	public function setSelected($selected){
		$this->selectedValue = $selected;
	}

	/**
	 * Get the radio option by id
	 * 
	 * @return string
	 */
	public function getById($id){
		$html = '';
		if(self::$hidden_sent == false){
			$html = new InputHidden($this->name, '');
		}
		self::$hidden_sent = true;
		foreach($this->array as $element){
			if(is_array($element)){
				$element = (object)$element;
			}
			if($element->id !== $id){
				continue;
			}
			$checked = false;
			if((string)$element->id === (string)$this->selectedValue && $this->selectedValue !== null){
				$checked = true;
			}
			$html .= new InputRadio($this->name, $element->id, $checked, $element->name, $this->options);
		}
		return $html;
	}

	/**
	 * To string
	 *
	 * @return string
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
