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
class OptGroup extends Element
{
	public $label;
	public $value;
	/**
	 * Constructor
	 *
	 * @param string $display
	 * @param string $value
	 * @param string or array $options
	 * @author Justin Palmer
	 */
	function __construct($label, array $opt, $options=null)
	{
		$this->label = $label;
		foreach($opt as $value){
			$this->display .= $value;
		}
		parent::__construct($options);
	}
	/**
	 * @see Tag::start()
	 */
	public function start()
	{
		return '<optgroup label="' . $this->label . '"' . $this->options . ">";
	}
	/**
	 * @see Tag::end()
	 */
	public function end()
	{
		return '</optgroup>';
	}
}
