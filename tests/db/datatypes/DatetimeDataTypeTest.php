<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
 * @package tests
 */
/**
 * Description
 *
 * @package tests
 * @author Justin Palmer
 */
/**
*
*/
class DatetimeDataTypeTest extends PHPUnit_Framework_TestCase
{
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function setUp()
	{
		$this->o = new DatetimeDataType('2001-02-03 04:05:06');
	}
	/**
	 * @test
	 **/
	public function correctYearFormat() {
		$f = $this->o->format('Y');
		$this->assertEquals($f, '2001');
	}

	/**
	 * @test
	 **/
	public function correctMonthFormat() {
		$f = $this->o->format('m');
		$this->assertEquals($f, '02');
	}

	/**
	 * @test
	 **/
	public function correctDayFormat() {
		$f = $this->o->format('d');
		$this->assertEquals($f, '03');
	}

	/**
	 * @test
	 **/
	public function correctHourFormat() {
		$f = $this->o->format('H');
		$this->assertEquals($f, '04');
	}

	/**
	 * @test
	 **/
	public function correctMinuteFormat() {
		$f = $this->o->format('i');
		$this->assertEquals($f, '05');
	}

	/**
	 * @test
	 **/
	public function correctSecondFormat() {
		$f = $this->o->format('s');
		$this->assertEquals($f, '06');
	}


	/**
	 * @test
	 */
	public function age() {
		$d = new DateTime();
		$d->sub(new DateInterval('P18Y'));
		$this->o = new DatetimeDataType($d->format('Y-m-d'));
		$this->assertEquals($this->o->age('%Y'), '18');
	}



}
