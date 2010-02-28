<?php
/**
* 
*/
class A extends Tag
{
	
	function __construct($display, $path, $options=null)
	{
		parent::__construct($options);
		$this->display = $display;
		$this->href = $path;
	}
	public function start()
	{
		return '<a href="' . $this->href . '"' . $this->options . '>';
	}
	public function end()
	{
		return '</a>';
	}
}