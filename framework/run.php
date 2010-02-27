<?php
session_start();

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

try{
	//Figure out what page the user is trying to access.
	$route = $Router->findByPath();
	//Set the current routes information in the registry.
	Registry::set('pr-route', $route);

	//Create the controller vars for instantiation and calling.
	$controller = $route['controller'] . 'Controller';
	$action = $route['action'];
	//Instantiate the correct controller and call the action.
	$Controller = new $controller();
	if(!method_exists($Controller, $action))
		throw new NoActionException();
	$Controller->$action();	
	//This is a hack.  There is no way to get the method called from a class.
	$Controller->pr_action = $action;
}catch(NoRouteException $e){
	Registry::set('pr-route', array('controller' => '',
									'action' => 'prNoRoute',
									'requested' => $_SERVER['REQUEST_URI']));
	$Controller = new Controller();
	$Controller->pr_layout = null;
	$Controller->prNoRoute();
}catch(NoControllerException $e){	
	Registry::set('pr-route', array('controller' => '',
									'action' => 'prNoController',
									'requested' => $controller));
	$Controller = new Controller();
	$Controller->pr_layout = null;
	$Controller->prNoController();
}catch(NoActionException $e){	
	Registry::set('pr-route', array('controller' => '',
									'action' => 'prNoAction',
									'no-action' => $action,
									'no-controller'=> $controller));
	$Controller = new Controller();
	$Controller->pr_layout = null;
	$Controller->prNoAction();
}
//Register the controller with the Template.
$Template = new Template($Controller);