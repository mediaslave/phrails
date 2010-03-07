<?php
/**
* Creates an 'link' tag used in the header tag of most requests.
* 
* @todo take an array for the options.
* @author Justin Palmer
* @package html
*/
class Link extends Tag
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
		parent::__construct($options);
		$this->href = $path;
	}
	/**
	 * @see Tag::start()
	 */
	public function start()
	{
		return '<link href="' . $this->href . '"' . $this->options . " />";
	}
	/**
	 * @see Tag::end()
	 */
	public function end(){}
}