<?php
/**
* 
*/
class LinkTest extends PHPUnit_Framework_TestCase
{	
	/**
	 * @test
	 **/
	public function Without_options()
	{
		$control = '<link href="/home" />' . "\n";
		$a = new Link('/home');
		$this->assertEquals($control, $a->__toString());
	}
}
