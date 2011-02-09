<?php
require_once 'AnsiAdapterMock.php';
/**
 * 
 */
class AnsiAdapterTest extends PHPUnit_Framework_TestCase
{
	
	private $stub;
	
	public function setUp()
	{
		$this->stub = $this->getMockForAbstractClass('AnsiAdapterMock');
	}
	/**
	 * @test
	 * @covers AnsiAdapter::buildCreate
	 **/
	public function Build_create()
	{
		
	}
	
	/**
	 * @test
	 * @covers DatabaseAdapter::buildRead
	 **/
	public function Build_read()
	{
		
	}
	
	/**
	 * @test
	 * @covers DatabaseAdapter::buildUpdate
	 **/
	public function Build_update()
	{
		
	}
	
	/**
	 * @test
	 * @covers DatabaseAdapter::buildDelete
	 **/
	public function Build_delete()
	{
		
	}
	
	/**
	 * @test
	 * @covers DatabaseAdapter::tick
	 **/
	public function tick()
	{
		
	}
}
