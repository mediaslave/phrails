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
class LinkTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @test
	 **/
	public function Without_options()
	{
		$control = '<link href="/home" />' . "\n";
		$a = new Link('/home');
		$this->assertEquals($control, $a->__toString());
	}
}
