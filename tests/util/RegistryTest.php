<?php
/**
 * * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
 * @package tests
 */
/**
 * Description
 *
 * @package tests
 * @author Justin Palmer
 */
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
		$this->assertInstanceOf('Hash', Registry::$Hash);
	}
	/**
	 * @test
	 **/
	public function Can_we_set_and_get()
	{
		Registry::set('foo', 'bar');
		$this->assertEquals('bar', Registry::get('foo'));

		Registry::set('baz', array('quo'=>'fuo', 'one'=>1));
		$this->assertInstanceOf('stdClass', Registry::get('baz'));
	}
	/**
	 * @test
	 **/
	public function Can_we_export()
	{
		$this->assertArrayHasKey('foo', Registry::export());
	}
}
