<?php
/**
* 
*/
class DateTest extends PHPUnit_Framework_TestCase
{
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Shaun Gilliland
	 **/
	public function setUp()
	{
		$this->o = new DateRule();
	}
	/**
	 * @test
	 **/
	public function valid_dates()
	{
		$dates = array('1900-12-31',
				 '2011-11-11'
			      );
		foreach($dates as $date){
			$this->o->value = $date;
			$this->assertTrue($this->o->run());
		}
	}
	
	/**
	 * @test
	 **/
	public function invalid_dates()
	{
		$dates = array('7-20-1980',
			         '1899-13-32',
				   '1899-12-31',
				     '2100-34-55'
			      );
		foreach($dates as $date){
			$this->o->value = $date;
			$this->assertFalse($this->o->run());
		}	
	}
}
