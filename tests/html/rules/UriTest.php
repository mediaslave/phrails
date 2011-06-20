<?php
/**
* 
*/
class UriTest extends PHPUnit_Framework_TestCase
{
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Shaun Gilliland
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
		$uris = array('http://www.mediaslave.net',
			      'http://www.cetmanagement.com',
			      'http://www.cetvancouver.com',
			      'http://www.cetusa.org',
			      'www.cetmanagement.com',
			      'www.cetvancouver.com',
			      'www.cetusa.org',
			      'www.mediaslave.net',
			      'cetmanagement.com',
			      'cetvancouver.com',
			      'cetusa.org',
			      'mediaslave.net'
			     );
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
		$uris = array('mediaslave..net',
			      'cetmanagement...com',
			      'cetvancouver..com',
			      'cetusa..org',
			      'cetmanagement..com',
			      'cetvancouver..com',
			      'cetusa..org',
			      'www..mediaslave.net'
			     );
		foreach($uris as $uri){
			$this->o->value = $uri;
			$this->assertFalse($this->o->run());
		}	
	}
}
