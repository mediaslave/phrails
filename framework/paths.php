<?php
/**
 * load the framework paths
 */
set_include_path(get_include_path() . 
				 PATH_SEPARATOR . dirname(__FILE__) . '/__view__' . 
				 PATH_SEPARATOR . dirname(__FILE__) . '/db' .
				 PATH_SEPARATOR . dirname(__FILE__) . '/exceptions' . 
				 PATH_SEPARATOR . dirname(__FILE__) . '/html'. 
				 PATH_SEPARATOR . dirname(__FILE__) . '/template'. 
				 PATH_SEPARATOR . dirname(__FILE__) . '/util');

/**
 * If the user of the framework defines their own __autoload, we will let them
 * for them to do so, they will need to call our autoload if theirs does not load 
 * anything.
 */
if(!function_exists('__autoload')){
	function __autoload($class_name) {
		autoload($class_name);
	}				
}

/**
 * Autoload method to load items from the app.
 *
 * @param string $class_name
 * @package framework
 * @author Justin Palmer
 */
function autoload($class_name)
{
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
 * @param string $extension
 * @return void
 * @package framework
 * @author Justin Palmer
 */
function include_all_in_folder ($folder, $extension='.php') {
    foreach (glob($folder . '/*' . $extension) as $file) {
        include $file;
    }
}