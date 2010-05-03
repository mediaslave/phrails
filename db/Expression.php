<?php
/**
* 
*/
class Expression
{
	protected $expression;
	
	function __construct($expression)
	{
		$this->expression = $expression;
	}
	
	public function __toString()
	{
		return $this->expression;
	}
}