<?php
/**
 * Creates a css link.
 * @package html
 */
class LinkCss extends Link
{
	/**
	 * This tag does not have a end tag.
	 *
	 * @var boolean
	 */
	protected $hasEndTag = false;
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
		$path = $path . '.css';
		$app_path = Registry::get('pr-install-path');
		if($app_path != null)
			$path = $app_path . 'public/stylesheets/' . $path . '?' . time();
		parent::__construct($path, $options);
	}
}