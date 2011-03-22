<?php
/**
 * Database adapter
 *
 * @package db
 * @author Justin Palmer
 */				
abstract class AnsiAdapterMock extends AnsiAdapter
{
	public function __construct()
	{
		//overload the default constructor so that it does not try to connect to the database
	}
}
