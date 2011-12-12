<?php
/**
 * * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
 * @package tests
 */
/**
 * Description
 *
 * @package tests
 * @author Justin Palmer
 */
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
