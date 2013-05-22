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
class StringSelect extends Select
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
	function __construct($name, $selectedValue=null, $string, $options=null,$itemDelimiter=";",$nameValueDelimiter=":")
	{
		$options = $this->preparePrompt($options);
		$this->addOptions($string, $itemDelimiter, $nameValueDelimiter);	
		parent::__construct($name, $selectedValue, null, $options);
	}

	/**
	 * Add the options based off of the delimiters
	 * 
	 * @return void
	 */
	private function  addOptions($string, $itemDelimiter, $nameValueDelimiter){
		$items = explode($itemDelimiter, $string);
		foreach ($items as $nameValue) {
			$args = explode($nameValueDelimiter, $nameValue);
			if(sizeof($args) == 1){
				$this->display .= new Option($args[0]);
			}else{
				$this->display .= new Option($args[0], $args[1]);
			}
		}
	}
}
