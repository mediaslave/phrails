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
class ATest extends PHPUnit_Framework_TestCase
{
	/**
	 * @test
	 **/
	public function With_no_options()
	{
		$a = '<a href="/">home</a>' . "\n";
		$o = new A('home', '/');
		$this->assertEquals($a, $o->__toString());
	}
	/**
	 * @test
	 **/
	public function With_options()
	{
		$a = '<a href="/" class="foo" id="bar">home</a>' . "\n";
		$o = new A('home', '/', 'class:foo,id:bar');
		$this->assertEquals($a, $o->__toString());
	}
}
