<?php
/**
 * Base class for database rules
 *
 * @package html
 * @subpackage rules
 * @author Dave Kerschner
 **/
class DatabaseRule extends Rule
{
	
	public function __construct($customMessage='') {
		parent::__construct($customMessage);
	}
	
} // END class Rule