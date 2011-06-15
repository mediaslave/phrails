<?php
/**
* 
*/
class IntegerExtraTest extends PHPUnit_Framework_TestCase
{
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Shaun Gilliland
	 **/
	public function setUp()
	{
		$this->o = new IntegerExtraRule();
	}
	/**
	 * @test
	 **/
	public function valid_ints()
	{
		$ints = array(3,4,2,56,2,34);
		foreach($ints as $int){
			$this->o->value = $int;
			$this->assertTrue($this->o->run());
		}
	}
	
	/**
	 * @test
	 **/
	public function invalid_ints()
	{
		$ints = array('#','!','%','^','&');
		foreach($ints as $int){
			$this->o->value = $int;
			$this->assertFalse($this->o->run());
		}	
	}
}
