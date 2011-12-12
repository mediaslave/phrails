<?php
/**
 * * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
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
class DivTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @test
	 **/
	public function With_no_options()
	{
		$a = '<div>home</div>' . "\n";
		$o = new Div('home');
		$this->assertEquals($a, $o->__toString());
	}
	/**
	 * @test
	 **/
	public function With_options()
	{
		$a = '<div class="foo" id="bar">home</div>' . "\n";
		$o = new Div('home', 'class:foo,id:bar');
		$this->assertEquals($a, $o->__toString());
	}
}
