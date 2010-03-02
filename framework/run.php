<?php
session_start();

//Load all of the helper methods
include_all_in_folder(dirname(__FILE__) . '/helpers');

//Instantiate the Routes object so that the user can specify the routes for the project.
$Routes = new Routes;
//Instantiate the Router to figure out where we are at.
$Router = new Router;

//Bring in the user routes
include Registry::get('pr-routes-path');

//Process the request.
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
	//Make sure the user has implemented the action
	if(!method_exists($Controller, $action))
		throw new NoActionException();
	//Process the action
	$Controller->$action();	
	//This is a hack.  There is no way to get the method called from a class.
	$Controller->pr_action = $action;
}catch(NoRouteException $e){
	Registry::set('pr-route', array('controller' => '',
									'action' => 'prNoRoute',
									'requested' => $_SERVER['REQUEST_URI'],
									'view-type' => 'html'));
	$Controller = new Controller();
	$Controller->pr_layout = null;
	$Controller->prNoRoute();
}catch(NoControllerException $e){	
	Registry::set('pr-route', array('controller' => '',
									'action' => 'prNoController',
									'requested' => $controller,
									'view-type' => 'html'));
	$Controller = new Controller();
	$Controller->pr_layout = null;
	$Controller->prNoController();
}catch(NoActionException $e){	
	Registry::set('pr-route', array('controller' => '',
									'action' => 'prNoAction',
									'no-action' => $action,
									'no-controller'=> $controller,
									'view-type' => 'html'));
	$Controller = new Controller();
	$Controller->pr_layout = null;
	$Controller->prNoAction();
}
//Register the controller with the Template.
$Template = new TemplateCache($Controller);