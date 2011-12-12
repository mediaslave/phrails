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
class UriTest extends PHPUnit_Framework_TestCase
{
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function setUp()
	{
		$this->o = new UriRule();
	}
	/**
	 * @test
	 **/
	public function valid_uris()
	{
		$uris = array('http://www.mediaslave.net');
		foreach($uris as $uri){
			$this->o->value = $uri;
			$this->assertTrue($this->o->run());
		}
	}

	/**
	 * @test
	 **/
	public function invalid_uris()
	{
		$uris = array('http://&www.mediaslave.net');
		foreach($uris as $uri){
			$this->o->value = $uri;
			$this->assertFalse($this->o->run());
		}
	}
}
