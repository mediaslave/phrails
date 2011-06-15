<?php
/**
* 
*/
class LengthRangeTest extends PHPUnit_Framework_TestCase
{
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Shaun Gilliland
	 **/

	/**
	 * @test
	 **/
	public function valid_lenrange()
	{
		$min = 5;
		$max = 16;
		$this->o = new LengthRangeRule($min,$max);
		for ($i=0;$i<10;$i++){
			$ranges[$i] = rand($min,$max);		
		}
		
		foreach($ranges as $range){
			$this->o->value = $range;
			$this->assertTrue($this->o->run());
		}
	}
	
	/**
	 * @test
	 **/
	public function invalid_lenrange()
	{
		$min = 5;
		$max = 16;
		$minwrong = 17;
		$maxwrong = 24;
		$this->o = new LengthRangeRule($min,$max);
		for ($i=0;$i<10;$i++){
		$ranges[$i] = rand($minwrong,$maxwrong);
		}
		foreach($ranges as $range){
			$this->o->value = $range;
			$this->assertFalse($this->o->run());
		}	
	}
}
