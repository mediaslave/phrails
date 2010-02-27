<?php
/**
* 
*/
class RoutesHashTest extends PHPUnit_Framework_TestCase
{
	public $o;
	
	public function setUp()
	{
		$this->o = new RoutesHash();
	}
	/**
	 * @test
	 * @expectedException Exception
	 **/
	public function Throws_exception_when_no_name_is_set_first()
	{
		try {
	         $this->o->set('foo', 'bar');
	     }catch (Exception $expected) {
			return;
	     }
	 
	     $this->fail('An expected exception has not been raised.');
	}
	/**
	 * @test
	 * @expectedException Exception
	 **/
	public function Throws_exception_when_there_is_no_key_set()
	{
		try {
	         $this->o->get('foo');
	     }catch (Exception $exception) {
			return;
	     }
	 
	     $this->fail('An expected exception has not been raised.');
	}
	/**
	 * @test
	 **/
	public function Can_we_use_route()
	{
		$this->o->route('home', 'root', 'Home', 'index');
		$this->assertEquals('home', $this->o->get('home', 'name'));
		$this->assertEquals('root', $this->o->get('home', 'route'));
		$this->assertEquals('Home', $this->o->get('home', 'controller'));
		$this->assertEquals('index', $this->o->get('home', 'action'));
	}
}
