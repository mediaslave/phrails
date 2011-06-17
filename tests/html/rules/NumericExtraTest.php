<?php
/**
* 
*/
class NumericExtraTest extends PHPUnit_Framework_TestCase
{
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Shaun Gilliland
	 **/
	public function setUp()
	{
		$this->o = new NumericExtraRule();
	}
	/**
	 * @test
	 **/
	public function valid_numexs()
	{
		$numexs = array('12345',
				'123',
				  '34563456'
			     );
		foreach($numexs as $numex){
			$this->o->value = $numex;
			$this->assertTrue($this->o->run());
		}
	}
	
	/**
	 * @test
	 **/
	public function invalid_numexs()
	{
		$numexs = array('ThisIsNotValid234@',
				'@thisAndThat',
				  '423362hkdi',
				    'This Is Not Valid'
			      );
		foreach($numexs as $numex){
			$this->o->value = $numex;
			$this->assertFalse($this->o->run());
		}	
	}
}
