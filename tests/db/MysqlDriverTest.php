<?php
/**
 * @todo why does this mock builder not work?
 * @error PDOException: You cannot serialize or unserialize PDO instances when ran
 */
class MysqlDriverTest extends PHPUnit_Framework_TestCase
{
	
	/**
	 * @test
	 * @covers MySqlDriver
	 **/
	public function Mock_constructor()
	{
		// $driver = $this->getMockBuilder('MysqlDriver')
		// 		               ->disableOriginalConstructor()
		// 		               ->getMock();
	}
}
