<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
 * @package framework
 * @author Justin Palmer
 */
//Load any plugin ini files and store them in pr-plugin-$filename
$plugin_inis = "config/*.ini";
foreach (glob($plugin_inis) as $filename) {
	if($filename != 'config/database.ini'){
		$ini = parse_ini_file($filename, true);
		$rkey = str_replace('config/', '', $filename);
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
}
