<?php

class PhrailsMigration extends Model{
	
	public function init(){
		$stmt = self::$db->pdo->prepare('CREATE TABLE IF NOT EXISTS `phrails_migrations` (
		  				`version` varchar(14) NOT NULL,
						  KEY `version` (`version`)
						) ENGINE=MyISAM');
		$stmt->execute();
		$this->columns = $this->prepareShowColumns($this->showColumns());
	}
	
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function save()
	{
		$s = self::$db->pdo->prepare("INSERT INTO `" . $this->database_name() . "`.`" . $this->table_name() . "` SET version=?;");
		return $s->execute(array($this->version));
	}
}
