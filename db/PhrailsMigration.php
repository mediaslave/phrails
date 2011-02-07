<?php

class PhrailsMigration extends Model{
	
	public function init(){
		$stmt = $this->conn()->prepare('CREATE TABLE IF NOT EXISTS `phrails_migrations` (
		  				`version` varchar(14) NOT NULL,
						  KEY `version` (`version`)
						) ENGINE=MyISAM');
		$stmt->execute();
		$this->columns = $this->adapter()->cacheColumns(get_class($this), $this->table_name);
	}
	
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function save()
	{
		$this->where('version = ?', $this->version);
		return $this->save();
	}
}
