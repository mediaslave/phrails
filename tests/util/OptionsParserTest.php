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
class OptionsParserTest extends PHPUnit_Framework_TestCase
{
	public $s;

	public function setUp()
	{
		$this->s = 'id:1,foo:bar';
	}
	/**
	 * @test
	 */
	public function Convert_toString()
	{
		$this->assertTrue(is_string(OptionsParser::toHtmlProperties($this->s)));
	}
	/**
	 * @test
	 */
	public function Convert_toArray()
	{
		$this->assertTrue(is_array(OptionsParser::toArray($this->s)));
	}
}
