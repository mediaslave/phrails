<?php
/**
 * load the framework paths
 */
add_include_directory(dirname(__FILE__) . '/__view__');				
add_include_directory(dirname(__FILE__) . '/db');
add_include_directory(dirname(__FILE__) . '/exceptions');
add_include_directory(dirname(__FILE__) . '/html');
add_include_directory(dirname(__FILE__) . '/html/rules');
add_include_directory(dirname(__FILE__) . '/template');
add_include_directory(dirname(__FILE__) . '/util');

/**
 * If the user of the framework defines their own __autoload, we will let them
 * for them to do so, they will need to call our autoload if theirs does not load 
 * anything.
 */

//If the user defines an __autoload then we will load it 
if(function_exists('__autoload'))
	spl_autoload_register('__autoload');
//Load our autoload function.
spl_autoload_register('autoload');
/**
 * Autoload method to load items from the app.
 *
 * @param string $class_name
 * @package framework
 * @author Justin Palmer
 */
function autoload($class_name){
	/*$string = explode('\\', $class_name);
	$namespace = array_shift($string);
	$klass = array_pop($string);
	if($namespace == PR_APPLICATION_NAMESPACE){
		$included = include_once($klass . '.php');
	}else{
		$file = str_replace('\\', '/', $class_name) . '.php';
		$included = include_once($file);
	}*/
	$path = str_replace(PR_APPLICATION_NAMESPACE, '', $class_name);
	//print $path . '<br/>';
	$included = include_once(ltrim(str_replace('\\', '/', $path), '/') . '.php');
	
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
 * @param boolean $do_folders
 * @return void
 * @package framework
 * @author Justin Palmer
 */
function include_all_in_folder ($folder, $extension='.php', $do_folders=false) {
	$glob = $folder . '/*' . $extension;
	$glob = glob($glob);
	if($do_folders)
		$glob = array_merge($glob, glob($folder . '/*', GLOB_ONLYDIR));
	foreach ($glob as $file) {
		if(is_dir($file)){
			include_all_in_folder($file, $extension, $do_folders);
			continue;
		}
        include $file;
    }
}

/**
 * Add an include directory for the project
 *
 * @return void
 * @author Justin Palmer
 **/
function add_include_directory($path)
{
	set_include_path(get_include_path() . PATH_SEPARATOR . $path);
}
/**
 * Initialize the plugin.
 *
 * @return void
 * @author Justin Palmer
 **/
function add_plugin($plugin, $alt_path=null)
{
	$path = "vendor/plugins/$plugin";
	if($alt_path !== null)
		$path = "$alt_path/$plugin";
	add_include_directory($path);
	include $path . "/init.php";
}

/**
 * Set the install path.
 *
 * @return void
 * @author Justin Palmer
 **/
function set_install_path($base_uri='/')
{
	//If it is set in the server config, then we will use it.
	if(isset($_SERVER['PHRAILS_BASE_URI']))
		$base_uri = $_SERVER['PHRAILS_BASE_URI'];
	//Set the install path.
	Registry::set('pr-install-path', $base_uri);
}
