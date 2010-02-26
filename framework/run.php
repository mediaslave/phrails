<?php
set_include_path(get_include_path() . 
				 PATH_SEPARATOR . './app/controllers' . 
				 PATH_SEPARATOR . './app/models' . 
				 PATH_SEPARATOR . './app/views' . 
				 PATH_SEPARATOR . dirname(__FILE__) . '/__view__' . 
				 PATH_SEPARATOR . dirname(__FILE__) . '/exceptions' . 
				 PATH_SEPARATOR . dirname(__FILE__) . '/helpers' . 
				 PATH_SEPARATOR . dirname(__FILE__) . '/html'. 
				 PATH_SEPARATOR . dirname(__FILE__) . '/template'. 
				 PATH_SEPARATOR . dirname(__FILE__) . '/util');
				

//Load the default view helpers
include 'view.php';

//Load the autoload mechanism
include 'autoload.php';

//Instantiate the Routes object so that the user can specify the routes for the project.
$Routes = new Routes;
//Instantiate the Router to figure out where we are at.
$Router = new Router;

//Bring in the user routes
include './config/routes.php';
//Registry::get('pr-routes-path');


//Figure out what page the user is trying to access.
$route = $Router->findByPath();

//Set the current routes information in the registry.
Registry::set('pr-route', $route);

//Create the controller vars for instantiation and calling.
$controller = $route['controller'] . 'Controller';
$action = $route['action'];
//Instantiate the correct controller and call the action.
try{
	$Controller = new $controller();
	$Controller->$action();
}catch(Exception $e){
	$route = Registry::get('pr-route');
	$controller = $route['controller'] . 'Controller';
	$action = $route['action'];
	$Controller = new $controller();
	$Controller->$action();
}
//This is a hack.  There is no way to get the method called from a class.
$Controller->pr_action = $action;

$Template = new Template($Controller);