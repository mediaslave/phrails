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
		$ranges = array("Hello","Length","January","November","September","Regulation"
					  ,"backlighted","abbreviation","abbreviations","ambidextrously"
					     ,"macroaggregated","lamentablenesses");
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
		$this->o = new LengthRangeRule($min,$max);
		$ranges = array("a","ab","abc","abcd","abcdefghijklmnopq","abcdefghijklmnopqr"
					  ,"abcdefghijklmnopqrs","abcdefghijklmnopqrst","abcdefghijklmnopqrstu","abcdefghijklmnopqrstuv"
					     ,"abcdefghijklmnopqrstuvw","abcdefghijklmnopqrstuvwx","abcdefghijklmnopqrstuvwxy","abcdefghijklmnopqrstuvwxyz");
		foreach($ranges as $range){
			$this->o->value = $range;
			$this->assertFalse($this->o->run());
		}	
	}
}
