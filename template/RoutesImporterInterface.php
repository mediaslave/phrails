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

interface RoutesImporterInterface{
	/**
	 * Validates that the importer can impor the routes.
	 * 
	 * @return boolean
	 * @throws RoutesImporterValidationException
	 */
	public function validate();

	/**
	 * Import the object into the Routes
	 * 
	 * @return Routes
	 */
	public function import();
}