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

class RoutesPluginImporter extends RoutesImporter{

	private $plugins = array();
	private $initialized_plugins = array();

	/**
	 * If no plugins passed in we will just load them all.
	 * Otherwise only load the plugins that are specified.
	 */
	function __construct(Routes $Routes/*, plugin_name[, plugin_name...]*/) {
		$args = func_get_args();
		$Routes = array_shift($args);
		parent::__construct($Routes);
		$this->plugins = $args;
		$this->validate();
	}

	/**
	 * Validates that the importer can impor the routes.
	 * 
	 * @return boolean
	 * @throws RoutesImporterValidationException
	 */
	public function validate(){
		//This means that we are just loading all of the available routes.php files from
		//each plugin that is loaded.
		if(empty($this->plugins)){
			$this->getInitializedPluginNames(true);
			return true;
		}

		//Otherwise let's make sure we have the plugins that they told us to load the routes.php file for.
		$plugins = $this->getInitializedPluginNames();

		foreach ($this->plugins as $plugin) {
			if(!in_array($plugin, $plugins)){
				throw new RoutesImporterValidationException('The Plugin "' . $plugin . '" was not initiliazed.');
			}
		}
	}

	/**
	 * Import the object into the Routes
	 * 
	 * @return Routes
	 */
	public function import(){

		foreach ($this->plugins as $plugin) {
			$path = $this->initialized_plugins[$plugin];
			$path = $path . '/config/routes.php';
			$Routes = $this->Routes;
			@include_once($path);
			$this->Routes = $Routes;
		}
		return parent::import();
	}

	/**
	 * Get the loaded plugins
	 * 
	 * @return array
	 */
	private function getInitializedPluginNames($isAll=false){
		$paths = Registry::get('pr-plugin-paths');
		$plugins = array();
		foreach ($paths as $plugin) {
			$path = explode('/', $plugin);
			$plugins[] = $plugin_name = array_pop($path);
			$this->initialized_plugins[$plugin_name] = $plugin;
			if($isAll){
				$this->plugins[] = $plugin_name;
			}
		}
		return $plugins;
	}
}