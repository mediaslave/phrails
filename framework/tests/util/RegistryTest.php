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
	public function Can_we_set_and_get()
	{
		Registry::set('foo', 'bar');
		$this->assertEquals('bar', Registry::get('foo'));
	}
	/**
	 * @test
	 **/
	public function Can_we_export()
	{
		$this->assertArrayHasKey('foo', Registry::export());
	}
}
