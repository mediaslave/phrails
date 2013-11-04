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
class ArrayInputCheckbox extends FormElement
{
	protected $array;
	/**
	 * Constructor
	 *
	 * @param string $display
	 * @param string or array $options
	 * @author Justin Palmer
	 */
	function __construct($name, $array, $options)
	{
		parent::__construct($options);
	}
	/**
	 * description...
	 * 
	 * @return boolean
	 */
	public function start(){return '';}
	/**
	 * description...
	 * 
	 * @return boolean
	 */
	public function end(){return '';}

	/**
	 * display
	 * 
	 * @return boolean
	 */
	public function display(){
		
	}

}
