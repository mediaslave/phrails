<?php
/**
* 
*/
class FormTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @test
	 **/
	public function With_no_options()
	{
		$a = '<form action="home">' . "\n";
		$o = new Form('home');
		$this->assertEquals($a, $o->__toString());
	}
	/**
	 * @test
	 **/
	public function With_options_with_remote()
	{
		$a = '<form action="home" class="foo" data-remote="true">' . "\n";
		$o = new Form('home', 'class:foo,remote:true');
		$this->assertEquals($a, $o->__toString());
	}
	/**
	 * @test
	 **/
	public function Get_end_tag()
	{
		$end = '</form>';
		$o = new Form('home', 'class:foo,remote:true');
		$this->assertEquals($a, $o->end());
	}
}
