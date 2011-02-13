<?php

class PhrailsMigration extends Model{
	
	protected $primary_key = 'version';
	
	public function init(){
		$this->columns = $this->adapter()->cacheColumns(get_class($this), $this->table_name);
	}
	
	/**
	 * Create the table if need be.
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function createTableIfNotExits()
	{
		$stmt = $this->conn()->prepare('CREATE TABLE IF NOT EXISTS `phrails_migrations` (
		  				`version` varchar(14) NOT NULL,
						  KEY `version` (`version`)
						) ENGINE=MyISAM');
		return $stmt->execute();
	}
}
