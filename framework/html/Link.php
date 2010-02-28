<?php
/**
* 
*/
class Link extends Tag
{
	protected $hasEndTag = false;
	
	function __construct($path, $options=null)
	{
		parent::__construct($options);
		$this->href = $path;
	}
	public function start()
	{
		return '<link href="' . $this->href . '"' . $this->options . " />";
	}
	public function end(){}
}