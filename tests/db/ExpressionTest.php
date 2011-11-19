<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE
 * @package tests
 */
/**
 * Description
 *
 * @package tests
 * @author Justin Palmer
 */
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
