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
		$s = $this->conn()->prepare("INSERT INTO `" . $this->database_name() . "`.`" . $this->table_name() . "` SET version = ?;");
		return $s->execute(array($this->version));
	}
}
