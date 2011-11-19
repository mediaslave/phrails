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
class HashArrayTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @test
	 **/
	public function Set_a_value_have_it_go_to_the_array()
	{
		$o = new HashArray();
		$o->set('foo', 'bar');
		$o->set('foo', 'baz');
		$o->set('quo', 'faz');
		$this->assertTrue($o->isKey('foo'));
		$this->assertTrue($o->isKey('quo'));
		$control = array('foo' =>
								array('bar', 'baz'),
						 'quo' =>
								array('faz'));
		$this->assertEquals($control, $o->export());
	}
}
