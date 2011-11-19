<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE
 * @package db
 */

/**
* @package db
* @author Justin Palmer
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
