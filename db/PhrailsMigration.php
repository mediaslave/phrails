<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE
 */

/**
 * @package db
 * @author Justin Palmer
 */
class PhrailsMigration extends Model{

	protected $primary_key = 'version';

	public function init(){

	}

	/**
	 * Create the table if need be.
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function createTableIfNotExists()
	{
		$stmt = $this->conn()->prepare('CREATE TABLE IF NOT EXISTS `phrails_migrations` (
		  				`version` varchar(14) NOT NULL,
						  KEY `version` (`version`)
						) ENGINE=MyISAM');
		$boolean =  $stmt->execute();
		$this->setColumns();
		return $boolean;
	}

	/**
	 * Special init because the table may not be there and the columns get cached, we need to call this after createTableIfNotExists
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function setColumns()
	{
		$this->columns = $this->adapter()->cacheColumns(get_class($this), $this->table_name, true);
	}
}
