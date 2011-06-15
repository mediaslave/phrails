<?php
/**
* 
*/
class BooleanTest extends PHPUnit_Framework_TestCase
{
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Shaun Gilliland
	 **/
	public function setUp()
	{
		$this->o = new BooleanRule();
	}
	/**
	 * @test
	 **/
	public function valid_yesornos()
	{
		$yesornos = array('1','0');
		foreach($yesornos as $yesorno){
			$this->o->value = $yesorno;
			$this->assertTrue($this->o->run());
		}
	}
	
	/**
	 * @test
	 **/
	public function invalid_yesornos()
	{
		$yesornos = array(2,3,67,null);
		foreach($yesornos as $yesorno){
			$this->o->value = $yesorno;
			$this->assertFalse($this->o->run());
		}	
	}
}
