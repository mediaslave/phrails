<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
 * @package template
 */
/**
 * class description
 *
 * @package template
 */

abstract class RoutesImporter implements RoutesImporterInterface{
	/**
	 * The routes to add the new routes to.
	 *
	 * @var Routes
	 */
	protected $Routes;

	function __construct(Routes $Routes) {
		$this->Routes = $Routes;
	}

	/**
	 * Import the object into the Routes
	 * 
	 * @return Routes
	 */
	public function import(){
		return $this->Routes;
	}
}