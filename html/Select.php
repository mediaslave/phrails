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
		//new \Dbug($selectedValue, '', false, __FILE__, __LINE__);
		//new \Dbug($args, '', false, __FILE__, __LINE__);
		//if $options is not an instance of Option we need to see if there
		//is a prompt option and prepare if so.
		if(!$options instanceof Option){
			$options = $this->preparePrompt($options);
		//If $options is an instance of Option, then we need to add it back to our
		//array of Option instances.
		}else{
			$args[] = $options;
			$options = null;
		}
		//If args is an array it could be an array of options.
		if(is_array($args[0]))
			$args = $args[0];
		$this->selectOptions($args, $selectedValue);
		parent::__construct($name, null, $options, array('method'=>'data-method', 'action'=>'data-action', 'remote'=>'data-remote'));
	}

	/**
	 * Select the right option or options
	 * 
	 * @return void
	 */
	protected function selectOptions($args, $selectedValue){
		//$args is now the optionTags after stripping out $name, $selectedValue and $options off of the
		//func_get_args array.
		if($args[0] !== null){
			foreach($args as $option){
				if(!($option instanceof Option))
					throw new Exception("Invalid tag for element 'Select'.  Only 'Option' elements may be passed.");
				if((string)$option->value === (string)$selectedValue && $selectedValue !== null)
					$option->selected = true;
				$this->display .= $option . "\n";
			}
		}
	}
	/**
	 * Check to see if we need to include a prompt.
	 *
	 * @return string
	 * @author Justin Palmer
	 **/
	protected function preparePrompt($options)
	{
		if($options !== null){
			$prompt = OptionsParser::findAndDestroy('prompt', $options);
			if($prompt != false)
				$this->display .= new Option($prompt, '');
			return OptionsParser::getOptions();
		}
		return null;
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
