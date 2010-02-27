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
	protected $options = '';
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
	protected function getArgs($array)
	{
		if(is_array($array[0]));
			$array = array_shift($array);
		return $array;
	}
}