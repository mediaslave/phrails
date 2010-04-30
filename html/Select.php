<?php
/**
 * Creates a 'label'.
 * 
 * @author Justin Palmer
 * @package html
 */
class Select extends FormElement
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
	function __construct($name, $selectedValue, $optionTags, $options=null)
	{
		$args = func_get_args();
		$name = array_shift($args);
		$selectedValue = array_shift($args);
		$options = array_pop($args);
		$options = $this->preparePrompt($options);
		if($args[0] !== null){
			foreach($args as $option){
				if(!($option instanceof Option))
					throw new Exception("Invalid tag for element 'Select'.  Only 'Option' elements may be passed.");
				if($option->value === $selectedValue)
					$option->selected = true;
				$this->display .= $option . "\n";
			}
		}
		parent::__construct($name, '', $options);
	}
	/**
	 * Check to see if we need to include a prompt.
	 *
	 * @return string
	 * @author Justin Palmer
	 **/
	protected function preparePrompt($options)
	{
		$prompt = OptionsParser::findAndDestroy('prompt', $options);
		if($prompt != false)
			$this->display .= new Option($prompt, '');
		return OptionsParser::getOptions();
	}
	/**
	 * @see Tag::start()
	 */
	public function start()
	{
		return '<select' . $this->options . '>';
	}
	/**
	 * @see Tag::end()
	 */
	public function end()
	{
		return '</select>';
	}
}