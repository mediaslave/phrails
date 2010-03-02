<?php
/**
* 
*/
class LinkCssTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @test
	 **/
	public function With_no_options()
	{
		$a = '<link href="screen.css" type="text/css" />';
		$o = new LinkCss('screen');
		$this->assertEquals($a, $o->__toString());
	}
	/**
	 * @test
	 **/
	public function With_options()
	{
		$a = '<link href="screen.css" type="text/css" media="all" />';
		$o = new LinkCss('screen', 'media:all');
		$this->assertEquals($a, $o->__toString());
	}
}
