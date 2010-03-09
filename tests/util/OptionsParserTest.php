<?php
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
		$this->assertType('string', OptionsParser::toString($this->s));	
	}
	/**
	 * @test
	 */
	public function Convert_toArray()
	{
		$this->assertType('array', OptionsParser::toArray($this->s));	
	}
}
