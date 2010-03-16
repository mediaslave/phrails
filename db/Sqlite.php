<?php
/**
 * Sqlite adapter
 *
 * @package db
 * @author Justin Palmer
 */				
class Sqlite extends Adapter
{
	/**
	 * Constructor
	 *
	 * @return Sqlite
	 * @author Justin Palmer
	 **/
	public function __construct($model)
	{
		parent::__construct($model);
	}
	/**
	 * Show columns
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function showColumns()
	{
		$table_name = $this->model->table_name();
		return parent::showColumns("PRAGMA table_info(`$table_name`)");
	}
}
