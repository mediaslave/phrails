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
class Script extends AssetTag
{
	protected $path = 'public/javascript/';
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
		parent::__construct($source . '.js', $options);
	}
	/**
	 * @see Tag::start
	 **/
	public function start()
	{
		return '<script src="' . $this->source . '" ' . $this->options . '>';
	}
	/**
	 * @see Tag::start
	 **/
	public function end(){
		return '</script>';
	}
}
