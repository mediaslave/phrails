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
require_once 'AnsiAdapterMock.php';
/**
 *
 */
class AnsiAdapterTest extends PHPUnit_Framework_TestCase
{

	private $stub;
	private $o;

	public function setUp()
	{
		$this->stub = $this->getMockForAbstractClass('AnsiAdapterMock');
		$this->o = new SqlBuilderHash;
	}
	/**
	 * @test
	 * @covers AnsiAdapter::buildCreate
	 **/
	public function Build_create()
	{
		$expected = new stdClass;
		$expected->sql = "INSERT INTO `users` (`id`,`first_name`,`last_name`) VALUES (?,?,?)";
		$expected->params = array(1, 'Justin', 'Palmer');

		$this->o->from('`users`');
		$this->o->props(new Hash(array('id' => 1,
									   'first_name' => 'Justin',
									   'last_name'  => 'Palmer')));
		$actual = $this->stub->buildCreate($this->o);
		$this->assertEquals($expected, $actual);

		//Using Expression
		$expected->sql = "INSERT INTO `users` (`id`,`first_name`,`last_name`,`created_on`) VALUES (?,?,?,NOW())";
		$this->o->props(new Hash(array('id' => 1,
									   'first_name' => 'Justin',
									   'last_name'  => 'Palmer',
									   'created_on' => new Expression('NOW()'))));
		$actual = $this->stub->buildCreate($this->o);
		$this->assertEquals($actual, $expected);

	}


	/**
	 * @test
	 * @covers AnsiAdapter::buildRead
	 **/
	public function Build_read()
	{
		$expected = new stdClass;
		$expected->sql = 'SELECT first_name, last_name , COUNT(DISTINCT user_id) AS count FROM `users` AS user INNER JOIN `tracks` AS track ON user.id = tracks.user_id WHERE foo = ? AND bar = ? GROUP BY user.id HAVING user.status = "active" ORDER BY first_name ASC,count DESC LIMIT 10';
		$expected->params = array('baz', 'quo');
		$this->o->select('first_name, last_name');
		$this->o->count('user_id', 'count', true);
		$this->o->from('`users` AS user');
		$this->o->join('INNER JOIN `tracks` AS track ON user.id = tracks.user_id');
		$this->o->where('foo = ? AND bar = ?');
		$this->o->whereArgs(array('baz', 'quo'));
		$this->o->group('user.id');
		$this->o->having('user.status = "active"');
		$this->o->order(array('first_name ASC', 'count DESC'));
		$this->o->offset(0);
		$this->o->limit(10);
		$actual = $this->stub->buildRead($this->o);
		$this->assertEquals($expected, $actual);
	}

	/**
	 * @test
	 * @covers AnsiAdapter::buildUpdate
	 **/
	public function Build_update()
	{
		$expected = new stdClass;
		$expected->sql = "UPDATE `users`  SET `first_name` = ?,`last_name` = ? WHERE id = ?";
		$expected->params = array('Justin', 'Palmer', 1);

		$this->o->from('`users`');
		$this->o->where('id = ?');
		$this->o->whereArgs(array(1));
		$this->o->props(new Hash(array('first_name' => 'Justin',
									   'last_name'  => 'Palmer')));
		$actual = $this->stub->buildUpdate($this->o);
		$this->assertEquals($expected, $actual);

		//With Expression
		$expected->sql = "UPDATE `users`  SET `first_name` = ?,`last_name` = ?,`created_on` = NOW() WHERE id = ?";
		$this->o->props(new Hash(array('first_name' => 'Justin',
									   'last_name'  => 'Palmer',
									   'created_on' => new Expression('NOW()'))));
		$actual = $this->stub->buildUpdate($this->o);
		$this->assertEquals($expected, $actual);
	}

	/**
	 * @test
	 * @covers AnsiAdapter::buildDelete
	 **/
	public function Build_delete()
	{
		$expected = new stdClass;
		$expected->sql = "DELETE FROM `users` WHERE id = ?";
		$expected->params = array(1);

		$this->o->from('`users`');
		$this->o->where('id = ?');
		$this->o->whereArgs(array(1));

		$actual = $this->stub->buildDelete($this->o);
		$this->assertEquals($expected, $actual);
	}

	/**
	 * @test
	 * @covers AnsiAdapter::tick
	 **/
	public function tick()
	{
		//Tick all items
		$actual = $this->stub->tick('foo', 'bar', 'baz');
		$expected = array('`foo`', '`bar`', '`baz`');
		$this->assertEquals($expected, $actual);

		//Tick a single item.
		$actual = $this->stub->tick('foo');
		$expected = '`foo`';
		$this->assertEquals($expected, $actual);
	}
}
