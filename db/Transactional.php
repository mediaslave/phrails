<?php

interface Transactional {
	/**
	 * Start a transaction
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function beginTransaction($savepoint);
	/**
	 * Commit a transaction
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function commit();
	/**
	 * Rollback a transaction
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function rollBack($savepoint);
}