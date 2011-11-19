<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE
 * @package db
 */

/**
* @package db
* @author Justin Palmer
*/
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
