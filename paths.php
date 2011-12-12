<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
 * @package framework
 */
add_include_directory(__DIR__ . '/__view__');
add_include_directory(__DIR__ . '/db');
add_include_directory(__DIR__ . '/db/datatypes');
add_include_directory(__DIR__ . '/exceptions');
add_include_directory(__DIR__ . '/html');
add_include_directory(__DIR__ . '/html/rules');
add_include_directory(__DIR__ . '/mail');
add_include_directory(__DIR__ . '/template');
add_include_directory(__DIR__ . '/util');

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
 */
function autoload($class_name){
	$path = str_replace(PR_APPLICATION_NAMESPACE, '', $class_name);
	$separated = explode('\\', $path);
	$class_name = array_pop($separated);
	if(sizeof($separated) > 0){
		$path = implode('\\', $separated);
		$path = strtolower(preg_replace('%\\\\-%', '\\', preg_replace('/([^\s])([A-Z])/', '\1-\2', $path))) . '\\' . $class_name;
	}else{
		$path = $class_name;
	}

	$included = include_once(ltrim(str_replace('\\', '/', $path), '/') . '.php');

	if($included === false){
		//die($class_name);
		if(substr($class_name, -10) == 'Controller'){
			eval("class $class_name{};");
			throw new NoControllerException();
		}else{
			throw new AutoloadException($class_name);
		}
	}
}

/**
 * Include all of the files in a folder that end with .php.
 *
 * @param string $folder
 * @param string $extension
 * @param boolean $do_folders
 * @return void
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
        include_once $file;
    }
}

/**
 * Add an include directory for the project
 *
 * @param string $path
 * @return void
 **/
function add_include_directory($path)
{
	set_include_path(get_include_path() . PATH_SEPARATOR . realpath($path));
}
/**
 * Initialize the plugin.
 *
 * @param string $plugin
 * @param string $alt_path
 * @return void
 **/
function add_plugin($plugin, $alt_path=null)
{
	$path = "vendor/plugins/$plugin";
	if($alt_path !== null)
		$path = "$alt_path/$plugin";
	add_include_directory($path);
	Registry::setInArray('pr-plugin-paths', realpath($path));
	include $path . "/init.php";
}

/**
 * Set the install path.
 *
 * @param string $base_uri
 * @return void
 **/
function set_install_path($base_uri='/')
{
	//If it is set in the server config, then we will use it.
	if(isset($_SERVER['PHRAILS_BASE_URI']))
		$base_uri = $_SERVER['PHRAILS_BASE_URI'];
	//Set the install path.
	Registry::set('pr-install-path', $base_uri);
}
