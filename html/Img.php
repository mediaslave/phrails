<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE
 */
/**
 * class description
 *
 * @package html
 * @author Justin Palmer
 */
class Img extends AssetTag
{
	/**
	 * This tag does not have a end tag.
	 *
	 * @var boolean
	 */
	protected $hasEndTag = false;

	/**
	 * The source of the url.
	 *
	 * @var string
	 */
	protected $source;

	protected $path = 'public/images/';
	/**
	 * Return a linkCss object
	 *
	 * @param string $source
	 * @param string $options
	 * @return LinkCss
	 * @author Justin Palmer
	 */
	function __construct($source, $options='', $from_base=true)
	{
		parent::__construct($source, $options, $from_base);
	}
	/**
	 * @see Tag::start
	 **/
	public function start()
	{
		return '<img src="' . $this->source . '" ' . $this->options . '/>';
	}
	/**
	 * @see Tag::start
	 **/
	public function end(){}
}
