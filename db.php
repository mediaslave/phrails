<?php
//We seperate this from run so that we can run rake tasks and other items
//when the app is not running.
$db_config = parse_ini_file('database.ini', true);
if($db_config['global']['need_database'] == true){
	if(isset($db_config[Registry::get('pr-environment')])){
		Registry::set('pr-db-config', $db_config[Registry::get('pr-environment')]);
	}else{
		throw new Exception('Phrails could not find the db config specified by the environment.');
	}
}