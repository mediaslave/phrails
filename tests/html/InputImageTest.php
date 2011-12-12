<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
 * @package tests
 */
/**
 * Description
 *
 * @package tests
 */
/**
*
*/
class InputImageTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @test
	 **/
	public function With_no_options()
	{
		$a = '<input type="image" src="/images/input.jpg" alt="input" name="inputname" id="inputname_id" value="" />' . "\n";
		$o = new InputImage('inputname', '/images/input.jpg', 'input');
		$this->assertEquals($a, $o->__toString());
	}
	/**
	 * @test
	 **/
	public function With_options_and_id()
	{
		$a = '<input type="image" src="/images/input.jpg" alt="input" name="inputname" id="inputname_id" class="foo" value="" />' . "\n";
		$o = new InputImage('inputname', '/images/input.jpg', 'input', 'class:foo');
		$this->assertEquals($a, $o->__toString());
	}
}
