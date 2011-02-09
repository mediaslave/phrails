<?php
/**
 * 
 */
class ExpressionTest extends PHPUnit_Framework_TestCase
{
	
	/**
	 * @test
	 **/
	public function Expression_to_string()
	{
		$o = new Expression('NOW()');
		$this->assertEquals('NOW()', $o->__toString());
	}
}
