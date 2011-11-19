<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE
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
		$this->assertEquals('bar', $this->o->foo);
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
	/**
	 * @test
	 **/
	public function Can_we_remove_and_is_empty()
	{
		$this->o->remove('foo');
		$array = $this->o->export();
		$this->assertTrue(empty($array));
		$this->assertTrue($this->o->isEmpty());
	}

	/**
	 * @test
	 **/
	public function Can_we_empty()
	{
		$this->o->clear();
		$this->assertTrue($this->o->isEmpty());
	}
}
