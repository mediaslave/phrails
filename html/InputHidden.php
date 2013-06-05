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
class InputHidden extends Input
{
	protected $options = 'type:hidden';

	protected $is_hidden = true;
	/**
	 * Constructor
	 *
	 * @param string $display
	 * @param string or array $options
	 * @author Justin Palmer
	 */
	function __construct($name, $value, $options=null)
	{
		$options = $this->destroyHiddenId($options);
		parent::__construct($name, $value, $options);
	}

	/**
	 * Should we destroy the hidden id
	 * 
	 * @return void
	 */
	public function destroyHiddenId($options){
		if($options !== null && OptionsParser::find('no-id', $options)){
			$prompt = OptionsParser::findAndDestroy('no-id', $options);
			$prompt = OptionsParser::findAndDestroy('id', $options);
			return OptionsParser::getOptions();
		}
		return $options;
	}
}
