<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
 * @package framework
 */
//Load any plugin ini files and store them in pr-plugin-$filename
$plugin_inis = Registry::get('pr-real-install-path') . "/config/*.ini";
foreach (glob($plugin_inis) as $filename) {
	if($filename == Registry::get('pr-real-install-path') . '/config/database.ini'){
		continue;
	}	
	//otherwise process the ini.
	$ini = parse_ini_file($filename, true);
	$rkey = str_replace(Registry::get('pr-real-install-path') . '/config/', '', $filename);
	$rkey = str_replace('.ini', '', $rkey);
	$obj = new stdClass;

	if(isset($ini[Registry::get('pr-environment')])){
		$obj = (object) $ini[Registry::get('pr-environment')];
	}
	if(isset($ini['global'])){
		$obj->{'global'} = (object) $ini['global'];
	}
	Registry::set('pr-plugin-' . $rkey, $obj);
}
