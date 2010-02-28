<?php
error_reporting(E_ALL);

//Load the environment
include 'config/environment.php';

/*
 * Changes this to where you have the framework installed.
 * This allows you to share one fromework for multiple projects.
 * Move the framework folder where ever you want, just change this var.
*/
$framework_install_path = 'framework';

//include the framework paths
include $framework_install_path . '/paths.php';


include_all_in_folder(dirname(__FILE__) . '/app/helpers');

//Set where the routes file is.
Registry::set('pr-routes-path', dirname(__FILE__) . '/config/routes.php');

//Set where the app is installed.
//  '/' represents the application being installed on the root.
Registry::set('pr-install-path', '/');

//Process the request.
include $framework_install_path . '/run.php';

//print '<pre>';
//print_r($Template->vars());
//print_r(Registry::export());

//Display the view.
print $Template->display();
