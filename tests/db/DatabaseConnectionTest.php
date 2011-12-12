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
