<?php
/**
* 
*/
class SelectTest extends PHPUnit_Framework_TestCase
{
	
	/**
	 * @test
	 **/
	public function construct()
	{
		$o = new Select('state', 'CA', new Option('Washington', 'WA'), new Option('California', 'CA'), 'class:foo');
		
		$expected = '<select name="state" id="state_id" class="foo">
						<option value="WA">Washington</option>  
						<option value="CA" selected="selected">California</option>
					</select>';
		
		$this->assertXmlStringEqualsXmlString($expected, $o->__toString());
	}
	
	/**
	 * @test
	 **/
	public function construct_with_no_options()
	{
		$o = new Select('state', 'CA', new Option('Washington', 'WA'), new Option('California', 'CA'));
		
		$expected = '<select name="state" id="state_id">
						<option value="WA">Washington</option>  
						<option value="CA" selected="selected">California</option>
					</select>';
		$this->assertXmlStringEqualsXmlString($expected, $o->__toString());
	}
	
	/**
	 * @test
	 **/
	public function construct_with_prompt_no_select()
	{
		$o = new Select('state', null, new Option('Washington', 'WA'), new Option('California', 'CA'), 'prompt: - Select One - , class:foo');
		
		$expected = '<select name="state" id="state_id" class="foo">
						<option value="">- Select One -</option>
						<option value="WA">Washington</option>  
						<option value="CA">California</option>
					</select>';
		
		$this->assertXmlStringEqualsXmlString($expected, $o->__toString());
	}
	
	/**
	 * @test
	 **/
	public function selected_value_is_and_is_not_equal_with_type_check($value='')
	{
		$is = new Select('state', '0', new Option('Washington', '0'), new Option('California', '1'));
		$is_not = new Select('state', 0, new Option('Washington', '0'), new Option('California', '1'));
		
		$expected = '<select name="state" id="state_id">
						<option value="0" selected="selected">Washington</option>  
						<option value="1">California</option>
					</select>';
		
		$this->assertXmlStringEqualsXmlString($expected, $is->__toString());		
		$this->assertXmlStringEqualsXmlString($expected, $is_not->__toString());
	}
	
	/**
	 * @test
	 **/
	public function construct_with_array_as_optionsTags_with_options_and_without_options()
	{
		$with = new Select('state', '0', array(new Option('Washington', '0'), new Option('California', '1')), 'class:foo');
		$with_out = new Select('state', '0', array(new Option('Washington', '0'), new Option('California', '1')));
		$with_only_prompt = new Select('state', '0', array(new Option('Washington', '0'), new Option('California', '1')), 'prompt: - Select One - ');
		
		$expected = '<select name="state" id="state_id" class="foo">
						<option value="0" selected="selected">Washington</option>  
						<option value="1">California</option>
					</select>';			
		
		$expected_without = '<select name="state" id="state_id">
						<option value="0" selected="selected">Washington</option>  
						<option value="1">California</option>
					</select>';
					
		$expected_with_prompt = '<select name="state" id="state_id">
								 	<option value="">- Select One -</option>
								 	<option value="0" selected="selected">Washington</option>  
								 	<option value="1">California</option>
								 </select>';
		
		$this->assertXmlStringEqualsXmlString($expected, $with->__toString());
		$this->assertXmlStringEqualsXmlString($expected_without, $with_out->__toString());
		$this->assertXmlStringEqualsXmlString($expected_with_prompt, $with_only_prompt->__toString());
	}
}
