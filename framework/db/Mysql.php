<?php
/**
 * Mysql adapter
 *
 * @package database
 * @author Justin Palmer
 */				
class Mysql extends Adapter
{
	
	/**
	 * Constructor
	 *
	 * @return Mysql
	 * @author Justin Palmer
	 **/
	public function __construct($model)
	{
		self::getConfig();
		parent::__construct($model, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . self::$Config->encoding));
	}
}
