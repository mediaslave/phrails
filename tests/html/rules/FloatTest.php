<?php
/**
* 
*/
class FloatTest extends PHPUnit_Framework_TestCase
{
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Shaun Gilliland
	 **/
	public function setUp()
	{
		$this->o = new FloatRule();
	}
	/**
	 * @test
	 **/
	public function valid_floats()
	{
		$floats = array(23.2344543443,1.23,0.4345,12345.4,3500.345656,342.323,3.1415926535897932384626433);
		foreach($floats as $float){
			$this->o->value = $float;
			$this->assertTrue($this->o->run());
		}
	}
	
	/**
	 * @test
	 **/
	public function invalid_floats()
	{
		$floats = array('http://&www.mediaslave.net',1,000,342342,232,89878);
		foreach($floats as $float){
			$this->o->value = $float;
			$this->assertFalse($this->o->run());
		}	
	}
}
