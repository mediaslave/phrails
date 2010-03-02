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

//Call the action
$action = $Controller->pr_action;
$Controller->$action();

//Register the controller with the Template.
$Template = new TemplateCache($Controller);