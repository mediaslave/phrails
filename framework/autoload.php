<?php

function __autoload($class_name) {
	$file = $class_name . '.php';
	$included = include_once($file);
	if($included === false){
		//Declaring the class with eval is a hack
		//__autoload exception throwing is not officially supported until 5.3
		eval("class $class_name{};");
		Registry::set('pr-route', array('controller' => '',
										'action' => 'prNoController',
										'requested-controller' => $class_name));
		throw new Exception('Houston we have a problem! No Controller.');
	}
}