<?php
/**
* 
*/
class LengthTest extends PHPUnit_Framework_TestCase
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
	public function valid_length()
	{
		$length = 8;
		$this->o = new LengthRule($length);
		$ranges = array("November","sabering","saboteur"
				   ,"sacredly","saintdom","saliency"
				      ,"salmonid","saltwork");
		foreach($ranges as $range){	
			$this->o->value = $range;
			$this->assertTrue($this->o->run());
		}
	}
	
	/**
	 * @test
	 **/
	public function invalid_length()
	{
		$length = 8;
		$this->o = new LengthRule($length);
		$ranges = array("a","ab","abc"
				   ,"abcd","abcde","abcdef"
				      ,"abcdefg","abcdefghi");
		foreach($ranges as $range){
			$this->o->value = $range;
			$this->assertFalse($this->o->run());
		}	
	}
}
