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
class RoutesHashTest extends PHPUnit_Framework_TestCase
{
	public $o;

	public function setUp()
	{
		$this->o = new RoutesHash();
	}
	/**
	 * @test
	 **/
	public function Throws_exception_when_name_is_not_set_first()
	{
		try {
	    	$this->o->set('foo', 'bar');
	    }catch (Exception $e) {
			return;
	    }
	    $this->fail('An expected exception has not been raised.');
	}
	/**
	 * @test
	 **/
	public function Throws_exception_when_there_is_no_key_set()
	{
		try {
	         $this->o->get('foo', 'bar');
	    }catch (Exception $e) {
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
		$this->assertEquals('root', $this->o->get('home', 'path'));
		$this->assertEquals('Home', $this->o->get('home', 'controller'));
		$this->assertEquals('index', $this->o->get('home', 'action'));
	}
}
