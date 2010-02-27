<?php
/**
* 
*/
class HashTest extends PHPUnit_Framework_TestCase
{
	public $o;
	
	public function setUp()
	{
		$array = array('foo'=>'bar');
		$this->o = new Hash($array);
	}
	/**
	 * @test
	 */
	public function Export()
	{
		$this->assertArrayHasKey('foo', $this->o->export());	
	}
	/**
	 * @test
	 **/
	public function Can_we_get()
	{
		$this->assertEquals('bar', $this->o->get('foo'));
	}
	/**
	 * @test
	 **/
	public function Can_we_set()
	{
		$this->o->set('baz', 'quo');
		$this->assertEquals('quo', $this->o->get('baz'));
	}
	/**
	 * @test
	 **/
	public function Is_the_key_set()
	{
		$this->assertTrue($this->o->isKey('foo'));
	}
}
