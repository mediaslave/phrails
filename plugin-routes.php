<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
 * @package framework
 */
//Load any routes that plugins may have.
foreach (Registry::get('pr-plugin-paths') as $path) {
	$file = $path . '/config/routes.php';
	if(is_file($file)){
		include $file;
	}
}