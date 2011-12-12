<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
 * @package tests
 */
/**
 * Description
 *
 * @package tests
 * @author Justin Palmer
 */
require_once 'AnsiAdapterMock.php';
/**
 *
 */
class SqlBuilderHashTest extends PHPUnit_Framework_TestCase
{

	private $o;
	/**
	 * The test array that should be the same as the internal
	 * array of SqlBuilderHash
	 */
	private $test;

	public function setUp()
	{
		$this->o = new SqlBuilderHash;
		$this->test = array('select'=>'*', 'where_args'=>array(), 'count'=>array(), 'order'=>array(), 'join'=>'');
	}

	/**
	 * @test
	 **/
	public function Constructor()
	{
		$this->assertEquals($this->test, $this->o->export());
	}

	/**
	 * @test
	 * @covers SqlBuilderHash::select
	 **/
	public function Select()
	{
		$select = 'foo, bar, baz';
		$this->o->select($select);
		$this->test['select'] = $select;
		$this->assertEquals($this->test, $this->o->export());
		$this->assertEquals($select, $this->o->select());
	}

	/**
	 * @test
	 * @covers SqlBuilderHash::from
	 **/
	public function From()
	{
		$from = '`table` AS foo_table';
		$this->o->from($from);
		$this->test['from'] = $from;
		$this->assertEquals($this->test, $this->o->export());
		$this->assertEquals($from, $this->o->from());
	}

	/**
	 * @test
	 * @covers SqlBuilderHash::join
	 **/
	public function Join()
	{
		$join ='foo_relationship';
		$this->o->join($join);
		$this->test['join'] = $join;
		$this->assertEquals($this->test, $this->o->export());
		$this->assertEquals($join, $this->o->join());
	}

	/**
	 * @test
	 * @covers SqlBuilderHash::where
	 **/
	public function Where()
	{
		$where = 'foo = ?';
		$this->o->where($where);
		$this->test['where'] = $where;
		$this->assertEquals($this->test, $this->o->export());
		$this->assertEquals($where, $this->o->where());
	}

	/**
	 * @test
	 * @covers SqlBuilderHash::whereArgs
	 **/
	public function Where_args()
	{
		$this->o->whereArgs(array('foo', 'bar'));
		$this->test['where_args'] = array('foo', 'bar');
		$this->assertEquals($this->test, $this->o->export());

		//Second call with array.
		$this->o->whereArgs(array('baz'));
		$this->test['where_args'] = array('foo', 'bar', 'baz');
		$this->assertEquals($this->test, $this->o->export());

		$this->assertEquals(array('foo', 'bar', 'baz'), $this->o->whereArgs());
	}

	/**
	 * @test
	 * @covers SqlBuilderHash::order
	 **/
	public function Order()
	{
		$order = array('foo DESC');
		$this->o->order($order);
		$this->test['order'] = $order;
		$this->assertEquals($this->test, $this->o->export());
		$this->assertEquals($order, $this->o->order());
	}

	/**
	 * @test
	 * @covers SqlBuilderHash::order
	 **/
	public function Order_when_array_contains_empty_string()
	{
		$order = array('', 'foo', '', 'bar');
		$expected_order = array('foo', 'bar');
		$this->o->order($order);
		$this->test['order'] = $expected_order;
		$this->assertEquals($this->test, $this->o->export());
		$this->assertEquals($expected_order, $this->o->order());
	}

	/**
	 * @test
	 * @covers SqlBuilderHash::limit
	 **/
	public function Limit()
	{
		$limit = 1;
		$this->o->limit($limit);
		$this->test['limit'] = $limit;
		$this->assertEquals($this->test, $this->o->export());
		$this->assertEquals($limit, $this->o->limit());
	}

	/**
	 * @test
	 * @covers SqlBuilderHash::offset
	 **/
	public function Offset()
	{
		$offset = 4;
		$this->o->offset($offset);
		$this->test['offset'] = $offset;
		$this->assertEquals($this->test, $this->o->export());
		$this->assertEquals($offset, $this->o->offset());
	}

	/**
	 * @test
	 * @covers SqlBuilderHash::group
	 **/
	public function Group()
	{
		$group = 'foo';
		$this->o->group($group);
		$this->test['group'] = $group;
		$this->assertEquals($this->test, $this->o->export());
		$this->assertEquals($group, $this->o->group());
	}

	/**
	 * @test
	 * @covers SqlBuilderHash::having
	 **/
	public function Having()
	{
		$having = 'bar';
		$this->o->having($having);
		$this->test['having'] = $having;
		$this->assertEquals($this->test, $this->o->export());
		$this->assertEquals($having, $this->o->having());
	}

	/**
	 * Yeah, Call_count, because obviously PHPUnit_Framework_TestCase has a count method
	 * and does not yell at me telling me so (PHPUnit mark it final!)
	 *
	 * @test
	 * @covers SqlBuilderHash::count
	 **/
	public function Call_count()
	{
		$o = new stdClass;
		$o->column = 'id';
		$o->as = 'count';
		$o->distinct = true;
		$this->o->count('id', 'count', true);
		$this->test['count'] = array($o);
		$this->assertEquals($this->test, $this->o->export());


		$o = new stdClass;
		$o->column = 'di';
		$o->as = 'count';
		$o->distinct = true;
		$this->o->count('di', 'count', true);
		$this->test['count'][] = $o;
		$this->assertEquals($this->test, $this->o->export());

		$this->assertEquals($this->test['count'], $this->o->count());

	}

	/**
	 * @test
	 * @covers SqlBuilderHash::props
	 **/
	public function Props()
	{
		$props = new Hash(array('bar'=>'baz'));
		$this->o->props($props);
		$this->test['props'] = $props;
		$this->assertEquals($this->test, $this->o->export());
		$this->assertEquals($props, $this->o->props());
	}
}
