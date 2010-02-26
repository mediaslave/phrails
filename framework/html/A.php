<?php
/**
* 
*/
class A extends Tag
{
	
	function __construct($display, $href="", $class='')
	{
		$this->display = $display;
		$this->href = $href;
		$this->class = $class;
	}
	public function start()
	{
		return '<a href="' . $this->href . '">';
	}
	public function end()
	{
		return '</a>';
	}
}