<?php
/**
* 
*/
class A extends Tag
{
	
	function __construct($display, $path, $options=null)
	{
		$this->display = $display;
		$this->href = $path;
		if($options !== null)
			$this->options = OptionsParser::toString($options);
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