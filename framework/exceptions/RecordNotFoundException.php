<?php
/**
* 
*/
class RecordNotFoundException extends Exception
{
	
	function __construct()
	{
		parent::__construct("The record could not be found.");
	}
}
