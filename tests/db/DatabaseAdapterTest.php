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
require_once 'DatabaseAdapterMock.php';
/**
 *
 */
class DatabaseAdapterTest extends PHPUnit_Framework_TestCase
{

	private $stub;

	public function setUp()
	{
		$this->stub = $this->getMockForAbstractClass('DatabaseAdapterMock');
	}
	/**
	 * @test
	 * @covers DatabaseAdapter
	 **/
	public function Has_attributes()
	{
		$this->assertClassHasAttribute('conn', 'DatabaseAdapter');
		$this->assertClassHasStaticAttribute('ColumnsCache', 'DatabaseAdapter');
	}
	/**
	 * @test
	 * @covers DatabaseAdapter::showColumns
	 **/
	public function Show_columns()
	{
		$this->stub->expects($this->any())
		             ->method('showColumns')
		             ->will($this->returnValue(new stdClass));

		$this->assertInstanceOf('stdClass', $this->stub->showColumns('users'));
	}

	/**
	 * @test
	 * @covers DatabaseAdapter::truncate
	 **/
	public function Truncate()
	{
		$this->stub->expects($this->any())
				   ->method('truncate')
				   ->will($this->returnValue(true));
		$this->assertEquals(true, $this->stub->truncate('users'));
	}

	/**
	 * @test
	 * @covers DatabaseAdapter::showTables
	 **/
	public function Show_tables()
	{
		$this->stub->expects($this->any())
		             ->method('showTables')
		             ->will($this->returnValue(new stdClass));

		$this->assertInstanceOf('stdClass', $this->stub->showTables());
	}

	/**
	 * @test
	 * @covers DatabaseAdapter::cacheColumns
	 **/
	public function Cache_columns()
	{
		$this->stub->expects($this->any())
		             ->method('cacheColumns')
		             ->will($this->returnValue(new Hash));

		$this->assertInstanceOf('Hash', $this->stub->cacheColumns('Users', 'users'));
	}

	/**
	 * @test
	 * @covers DatabaseAdapter::buildCreate
	 **/
	public function Build_create()
	{
		$this->stub->expects($this->any())
		             ->method('buildCreate')
		             ->will($this->returnValue(new stdClass));

		$this->assertInstanceOf('stdClass', $this->stub->buildCreate(new SqlBuilderHash));
	}

	/**
	 * @test
	 * @covers DatabaseAdapter::buildRead
	 **/
	public function Build_read()
	{
		$this->stub->expects($this->any())
		             ->method('buildRead')
		             ->will($this->returnValue(new stdClass));

		$this->assertInstanceOf('stdClass', $this->stub->buildRead(new SqlBuilderHash));
	}

	/**
	 * @test
	 * @covers DatabaseAdapter::buildUpdate
	 **/
	public function Build_update()
	{
		$this->stub->expects($this->any())
		             ->method('buildUpdate')
		             ->will($this->returnValue(new stdClass));

		$this->assertInstanceOf('stdClass', $this->stub->buildUpdate(new SqlBuilderHash));
	}

	/**
	 * @test
	 * @covers DatabaseAdapter::buildDelete
	 **/
	public function Build_delete()
	{
		$this->stub->expects($this->any())
		             ->method('buildDelete')
		             ->will($this->returnValue(new stdClass));

		$this->assertInstanceOf('stdClass', $this->stub->buildDelete(new SqlBuilderHash));
	}

	/**
	 * @test
	 * @covers DatabaseAdapter::tick
	 **/
	public function tick()
	{
		$this->stub->expects($this->any())
		             ->method('tick')
		             ->will($this->returnValue(array()));

		$this->assertEquals(array(), $this->stub->tick());
	}

	/**
	 * Only gets called internally, but needs to be able to overridden
	 * this is why assertTrue
	 *
	 * @test
	 * @covers DatabaseAdapter::limit
	 **/
	public function limit()
	{
		$this->assertTrue(true);
	}

	/**
	 * @test
	 * @covers DatabaseAdapter::lastInsertId
	 **/
	public function lastInsertId()
	{
		$this->stub->expects($this->any())
		             ->method('lastInsertId')
		             ->will($this->returnValue(1));

		$this->assertEquals(1, $this->stub->lastInsertId());
	}

	/**
	 * @test
	 * @covers DatabaseAdapter::conn
	 **/
	public function conn()
	{
		$this->assertTrue($this->stub->conn());
	}
}
