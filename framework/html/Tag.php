<?php
/**
* 
*/
abstract class Tag
{
	protected $hasEndTag = true;
	protected $display = '';
	protected $options = '';
	/**
	 * Constructor for the Tag class
	 *
	 * @return Tag
	 * @author Justin Palmer
	 **/
	public function __construct($options=null)
	{
		$this->options = OptionsParser::toString($this->addOptions($options));
	}
	/**
	 * @abstract
	 */
	abstract function start();
	/**
	 * @abstract
	 */
	abstract function end();
	/**
	 * Return the display string
	 * @return string
	 */
	public function display()
	{
		return $this->display;
	}
	/**
	 * Return the tag
	 * @return string
	 */
	public function __toString()
	{
		$end = '';
		if($this->hasEndTag)
			$end = $this->display() . $this->end();
		return $this->start() . $end;
	}
	/**
	 * Add an option to the options string
	 *
	 * @return string
	 * @return string
	 * @author Justin Palmer
	 **/
	private function addOptions($options)
	{
		$options = $this->options . ',' . $options;
		$this->options = ltrim(rtrim($options, ','), ',');
		return $this->options;
	}
}