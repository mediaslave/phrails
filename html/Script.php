<?php
/**
 * Creates a script tag.
 * @package html
 */
class Script extends Tag
{
	/**
	 * Return a script object
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
			$source = $app_path . 'public/javascript/' . $source . '?' . time();
				$this->options = "src:$source,type:text/javascript";
		parent::__construct($options);
	}
	/**
	 * @see Tag::start
	 **/
	public function start()
	{
		return '<script' . $this->options . '>';
	}
	/**
	 * @see Tag::start
	 **/
	public function end(){
		return '</script>';
	}
}