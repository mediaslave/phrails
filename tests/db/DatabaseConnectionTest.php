<?php
require_once 'DatabaseConnectionMock.php';
/**
 * 
 */
class DatabaseConnectionTest extends PHPUnit_Framework_TestCase
{
	
	/**
	 * @test
	 * @covers DatabaseConnection::connect
	 **/
	public function Connect()
	{
		$this->assertTrue(DatabaseConnectionMock::connect());
	}
	
	
	/**
	 * @test
	 * @covers DatabaseConnection::disconnect
	 **/
	public function Disconnect()
	{
		$conn = $this->getMock('DatabaseConnection', array('disconnect'));
	}
}
