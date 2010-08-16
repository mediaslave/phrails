<?php
//Load any plugin ini files and store them in pr-plugin-$filename
$plugin_inis = "config/*.ini";
foreach (glob($plugin_inis) as $filename) {	
	if($filename != 'config/database.ini'){
		$ini = parse_ini_file($filename, true);
		$rkey = str_replace('config/', '', $filename);
		$rkey = str_replace('.ini', '', $rkey);
		$obj = new stdClass;
		if(isset($ini[Registry::get('pr-environment')])){
			foreach($ini[Registry::get('pr-environment')] as $key => $value){
				$obj->$key = $value;
			}
			Registry::set('pr-plugin-' . $rkey, $obj);
		}
	}
}
