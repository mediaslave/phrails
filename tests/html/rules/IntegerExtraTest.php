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
	
	/**
	 * @test
	 **/
	public function valid_ints()
	{
		$extra = 'a-cA-C';
		$this->o = new IntegerExtraRule($extra);
		$ints = array('3','4','2','56','2','34','abc234','ABC123');
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
		$extra = 'a-cA-C';
		$this->o = new IntegerExtraRule($extra);
		$ints = array('#2','!1','%321','^123','&123','1234d','45356gghj','fghj','r123');
		foreach($ints as $int){
			$this->o->value = $int;
			$this->assertFalse($this->o->run());
		}	
	}
}
