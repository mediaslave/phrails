<?php
/**
 * Is the value for a given column unique in the db
 *
 * @package html
 * @subpackage rules
 * @author Dave Kerschner
 **/
class UniqueDatabaseRule extends DatabaseRule
{
	/**
	 * @see Rule::message
	 */
	public $message = '%s is already used. Please enter another one.';

	/**
	 * @see Rule::run()
	 **/
	 public function run(){
		$record = $this->model->where('`' . $this->property . '` = ? ', $this->value)->count();
		return parent::run($record->count);
	 }
} // END class Rule