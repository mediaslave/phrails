<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
 * @package tests
 */
/**
 * Description
 *
 * @package tests
 */
/**
*
*/
class ResultSetSelectTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @test
	 **/
	public function construct_with_options()
	{
		$array = array(array('name'=>'Washington', 'id'=>'WA'),
						array('name'=>'California','id'=>'CA'));

		$o = new ResultSetSelect('state', $array, 'CA', "remote:true,action:/states-by-country,data-name:country_id");

		$expected = '<select name="state" id="state_id" data-remote="true" data-action="/states-by-country" data-name="country_id">
						<option value="WA">Washington</option>
						<option value="CA" selected="selected">California</option>
					</select>';

		$this->assertXmlStringEqualsXmlString($expected, $o->__toString());
	}
}

