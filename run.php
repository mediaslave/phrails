<?php
session_start();

//Load all of the helper methods
include_all_in_folder(dirname(__FILE__) . '/helpers');

//Instantiate the Routes object so that the user can specify the routes for the project.
$Routes = new Routes;

//Bring in the user defined routes
include Registry::get('pr-routes-path');

//Instantiate the Router to figure out where we are at.
$Router = new Router;
//Route the request
$Controller = $Router->route();

//Register the controller with the Template.
$Template = new ControllerTemplate($Controller);

//Does this template have a cache?
$is_valid_type = $Template->isValidCacheType();
$is_cached = null;
if($is_valid_type)
	$is_cached = $Template->Cache->isCached();

//Call the action
$Controller->prRun($is_valid_type, $is_cached);