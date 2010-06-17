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
	 * The source of the url.
	 * 
	 * @var string
	 */
	protected $source;
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
		$app_path = Registry::get('pr-install-path');
		$rule = new UriRule();
		$rule->value = $source;
		if(!$from_base && $app_path != null && !$rule->run())
			$source = $app_path . 'public/images/' . $source . '?' . time();
		$this->source = $source;
		parent::__construct($options);
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