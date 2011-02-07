<?php

/**
* 
*/
class MysqlDriver extends PDO
{
	
	function __construct()
	{
		$Config = DatabaseConfiguration::getConfig();
		return parent::__construct($Config->driver . ":host=" . $Config->host . ";dbname=" . $Config->database, 
							 	   $Config->username, 
							 	   $Config->password, 
						 		   array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . $Config->encoding));
	}
}
