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
/**
*
*/
class WordCountRangeRuleTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @test
	 **/
	public function With_no_max()
	{
		$o = new WordCountRangeRule(2);
		$o->value = 'The ';
		$this->assertFalse($o->run());
		$o->value .= ' rule will pass.';
		$this->assertTrue($o->run());
	}
	/**
	 * @test
	 **/
	public function With_max()
	{
		$o = new WordCountRangeRule(2, 3);
		$o->value = 'The';
		$this->assertFalse($o->run());
		$o->value .= ' rule will pass.';
		$this->assertFalse($o->run());
	}
	/**
	 * @test
	 **/
	public function Minimum_and_maximum_bounds()
	{
		$o = new WordCountRangeRule(2, 4);
		$o->value = 'The rule';
		$this->assertTrue($o->run());
		$o->value = ' should pass.';
		$this->assertTrue($o->run());
	}
}
