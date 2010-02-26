<?php
/**
* 
*/
abstract class Tag
{
	protected $start;
	protected $end;
	protected $hasEnd = true;
	protected $display = '';
	abstract function start();
	abstract function end();
	public function display()
	{
		return $this->display;
	}
	public function __toString()
	{
		return $this->start() . $this->display() . $this->end();
	}
}