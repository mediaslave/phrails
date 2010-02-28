<?php
/**
* 
*/
abstract class Tag
{
	protected $hasEndTag = true;
	protected $display = '';
	protected $options = '';
	/**
	 * @abstract
	 */
	abstract function start();
	/**
	 * @abstract
	 */
	abstract function end();
	
	public function display()
	{
		return $this->display;
	}
	
	public function __toString()
	{
		$end = '';
		if($this->hasEndTag)
			$end = $this->display() . $this->end();
		return $this->start() . $end;
	}
}