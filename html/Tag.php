<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
 * @package html
 */
/**
 * class description
 *
 * @package html
 * @author Justin Palmer
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
	 * Combine the options that are set for any given tag, with the
	 * options the user wants to add and return it.
	 *
	 * @param mixed $options
	 * @return string
	 * @author Justin Palmer
	 **/
	private function addOptions($options)
	{
		try {
			return $this->options = array_merge(OptionsParser::toArray($this->options),
																				OptionsParser::toArray($options));
		} catch (OptionParserParseException $e) {
			throw new InvalidTagPropertiesException($this->options . ',' . $options);
		}
	}
}
