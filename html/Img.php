<?php
/**
 * Creates a img tag.
 * @package html
 */
class Img extends Tag
{
	/**
	 * This tag does not have a end tag.
	 *
	 * @var boolean
	 */
	protected $hasEndTag = false;
	/**
	 * Return a linkCss object
	 *
	 * @param string $source 
	 * @param string $options 
	 * @return LinkCss
	 * @author Justin Palmer
	 */
	function __construct($source, $options='')
	{
		$app_path = Registry::get('pr-install-path');
		if($app_path != null)
			$source = $app_path . 'public/images/' . $source . '?' . time();
				$this->options = "src:$source";
		parent::__construct($options);
	}
	/**
	 * @see Tag::start
	 **/
	public function start()
	{
		return '<img' . $this->options . '/>';
	}
	/**
	 * @see Tag::start
	 **/
	public function end(){}
}