<?php
/**
* 
*/
class MaleFemaleSelect extends Select
{
	
	function __construct($name=null, $selectedValue=null, $options=null)
	{
		parent::__construct($name, $selectedValue, 
						  new Option('Male'),
						  new Option('Female'),
						  $options);
	}
}
