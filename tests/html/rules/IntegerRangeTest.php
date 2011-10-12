<?php
/**
* 
*/
class IntegerRangeTest extends PHPUnit_Framework_TestCase
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
	public function valid_intrange()
	{
		$min = 3;
		$max = 95;
		$this->o = new IntegerRangeRule($min,$max);
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
	public function invalid_ranges()
	{
		$min = 3;
		$max = 95;
		$minwrong = 96;
		$maxwrong = 106;
		$this->o = new IntegerRangeRule($min,$max);
		for ($i=0;$i<10;$i++){
		$ranges[$i] = rand($minwrong,$maxwrong);
		}
		foreach($ranges as $range){
			$this->o->value = $range;
			$this->assertFalse($this->o->run());
		}	
	}
}
