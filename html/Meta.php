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
class Meta extends Tag
{
	protected $hasEndTag = false;

	private $name;
	private $content;
	/**
	 * Constructor
	 *
	 * @param string $name
	 * @param string $content
	 * @param string or array $options
	 * @author Justin Palmer
	 */
	function __construct($name, $content, $options=null)
	{
		parent::__construct($options);
		$this->name = $name;
		$this->content = $content;
	}
	/**
	 * @see Tag::start()
	 */
	public function start()
	{
		return '<meta name="' . $this->name . '" content="' . $this->content . '"' . $this->options . ' />';
	}

	/**
	 * @see Tag::end();
	 **/
	public function end(){}
}
