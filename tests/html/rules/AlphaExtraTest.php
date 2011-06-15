<?php
/**
* 
*/
class AlphaExtraTest extends PHPUnit_Framework_TestCase
{
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Shaun Gilliland
	 **/
	public function setUp()
	{
		$this->o = new AlphaExtraRule();
	}
	/**
	 * @test
	 **/
	public function valid_alexs()
	{
		$alexs = array('ThisIsValid',
				'TestingIsFun',
				  'FreDFlintstone'
			     );
		foreach($alexs as $alex){
			$this->o->value = $alex;
			$this->assertTrue($this->o->run());
		}
	}
	
	/**
	 * @test
	 **/
	public function invalid_alexs()
	{
		$alexs = array('ThisIsNotValid234@',
				'@thisAndThat',
				  '423362hkdi',
				    'This Is Not Valid'
			      );
		foreach($alexs as $alex){
			$this->o->value = $alex;
			$this->assertFalse($this->o->run());
		}	
	}
}
