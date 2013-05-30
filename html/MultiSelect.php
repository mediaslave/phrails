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
class MultiSelect extends Select
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
		parent::__construct($name, $selectedValue, $args, $options);
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
				if(is_array($selectedValue)){
					foreach ($selectedValue as $value) {
						if((string)$option->value === (string)$value && $value !== null)
							$option->selected = true;
					}
				}
				$this->display .= $option . "\n";
			}
		}
	}

	/**
	 * @see Tag::start()
	 */
	public function start()
	{
		return '<select' . $this->options . ' multiple>';
	}
}
