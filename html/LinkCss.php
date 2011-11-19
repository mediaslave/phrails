<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE
 * @package html
 */
/**
 * class description
 *
 * @package html
 * @author Justin Palmer
 */
class LinkCss extends Link
{
	/**
	 * This tag does not have a end tag.
	 *
	 * @var boolean
	 */
	protected $hasEndTag = false;

	protected $path = 'public/stylesheets/';
	/**
	 * Default the options.
	 *
	 * @var string
	 */
	protected $options = 'type:text/css,rel:stylesheet';
	/**
	 * Return a linkCss object
	 *
	 * @param string $path
	 * @param string $options
	 * @return LinkCss
	 * @author Justin Palmer
	 */
	function __construct($path, $options='')
	{
		parent::__construct($path . '.css', $options);
	}
}
