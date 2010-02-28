<?php
set_include_path(get_include_path() . 
				 PATH_SEPARATOR . dirname(__FILE__) . '/__view__' . 
				 PATH_SEPARATOR . dirname(__FILE__) . '/exceptions' . 
				 PATH_SEPARATOR . dirname(__FILE__) . '/html'. 
				 PATH_SEPARATOR . dirname(__FILE__) . '/template'. 
				 PATH_SEPARATOR . dirname(__FILE__) . '/util');

/**
 * Autoload method to load items from the app.
 *
 * @param string $class_name
 * @package framework
 * @author Justin Palmer
 */
function __autoload($class_name) {
	$file = $class_name . '.php';
	$included = include_once($file);
	if($included === false){
		//Declaring the class with eval is a hack
		//__autoload exception throwing is not officially supported until 5.3
		eval("class $class_name{};");
		throw new NoControllerException();
	}
}				
/**
 * Include all of the files in a folder that end with .php.
 *
 * @param string $folder
 * @package framework
 * @author Justin Palmer
 */
function include_all_in_folder ($folder) {
    foreach (glob($folder . '/*.php') as $file) {
        include $file;
    }
}