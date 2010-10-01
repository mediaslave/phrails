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
		$this->assertType('string', OptionsParser::toHtmlProperties($this->s));	
	}
	/**
	 * @test
	 */
	public function Convert_toArray()
	{
		$this->assertType(PHPUnit_Framework_Constraint_IsType::TYPE_ARRAY, OptionsParser::toArray($this->s));	
	}
}
