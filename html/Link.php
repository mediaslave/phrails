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
class Link extends AssetTag
{
	/**
	 * Constructor
	 *
	 * @param string $path
	 * @param string or array $options
	 * @author Justin Palmer
	 */
	function __construct($path, $options=null)
	{
		parent::__construct($path, $options);
	}
	/**
	 * @see Tag::start()
	 */
	public function start()
	{
		return '<link href="' . $this->source . '"' . $this->options . " />";
	}
	/**
	 * @see Tag::end()
	 */
	public function end(){}
}
