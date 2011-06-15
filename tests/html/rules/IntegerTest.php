<?php
/**
* 
*/
class IntegerTest extends PHPUnit_Framework_TestCase
{
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Shaun Gilliland
	 **/
	public function setUp()
	{
		$this->o = new IntegerRule();
	}
	/**
	 * @test
	 **/
	public function valid_ints()
	{
		$ascii = 48;
		for ($i=0;$i<10;$i++){
			$ints[$i] = chr($ascii);
			$ascii++;
		}	
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
		$ascii = 0;
		for ($i=0;$i<47;$i++){
			$ints[$i] = chr($ascii);
			$ascii++;
		}
		foreach($ints as $int){
			$this->o->value = $int;
			$this->assertFalse($this->o->run());
		}
		$ascii = 58;
		for ($i=0;$i<198;$i++){
			$intuppers[$i] = chr($ascii);
			$ascii++;
		}
		foreach($intuppers as $intupper){
			$this->o->value = $intupper;
			$this->assertFalse($this->o->run());
		}	
	}
}
