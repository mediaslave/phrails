<?php
/**
* 
*/
class RegistryTest extends PHPUnit_Framework_TestCase
{
	
	public function setUp()
	{
		
	}
	/**
	 * @test
	 **/
	public function Does_construct_have_hash()
	{
		$r = new Registry();
		$this->assertType('Hash', Registry::$Hash);
	}
	/**
	 * @test
	 **/
	public function Can_we_set_and_get()
	{
		Registry::set('foo', 'bar');
		$this->assertEquals('bar', Registry::get('foo'));
		
		Registry::set('baz', array('quo'=>'fuo', 'one'=>1));
		$this->assertType('stdClass', Registry::get('baz'));
	}
	/**
	 * @test
	 **/
	public function Can_we_export()
	{
		$this->assertArrayHasKey('foo', Registry::export());
	}
}
