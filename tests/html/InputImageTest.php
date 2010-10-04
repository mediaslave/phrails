<?php
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
		$a = '<input type="image" src="/images/input.jpg" alt="input" name="inputname" value="" id="inputname_id" />' . "\n";
		$o = new InputImage('inputname', '/images/input.jpg', 'input');
		$this->assertEquals($a, $o->__toString());
	}
	/**
	 * @test
	 **/
	public function With_options_and_id()
	{
		$a = '<input type="image" src="/images/input.jpg" alt="input" name="inputname" value="" id="inputname_id" class="foo" />' . "\n";
		$o = new InputImage('inputname', '/images/input.jpg', 'input', 'class:foo');
		$this->assertEquals($a, $o->__toString());
	}
}