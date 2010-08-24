<?php
/**
* Base class for all tags.
* @author Justin Palmer
* @package html
*/
abstract class Tag
{
	/**
	 * Does the current tag have an end tag?
	 *
	 * @author Justin Palmer
	 * @var boolean
	 */
	protected $hasEndTag = true;
	/**
	 * The display that appears in between the start and end tag.
	 *
	 * @author Justin Palmer
	 * @var string
	 */
	protected $display = '';
	/**
	 * The options for the current tag
	 *
	 * @author Justin Palmer
	 * @var string or array
	 */
	protected $options = '';
	/**
	 * Constructor for the Tag class
	 *
	 * @return Tag
	 * @author Justin Palmer
	 **/
	public function __construct($options=null, array $optionExceptions=array())
	{
		$this->options = OptionsParser::toHtmlProperties($this->addOptions($options), $optionExceptions);
	}
	/**
	 * Start tag for the current tag.
	 * @abstract
	 */
	abstract function start();
	/**
	 * End tag for the current tag.
	 * @abstract
	 */
	abstract function end();
	/**
	 * Return the display string
	 * @return string
	 */
	public function display($display = null)
	{
		if($display === null)
			return $this->display;
		$this->display = $display;
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
		return $this->start() . $end . "\n";
	}
	/**
	 * Get the options for this tag
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function options()
	{
		return $this->options;
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