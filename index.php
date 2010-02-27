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

//Set where the routes file is.
Registry::set('pr-routes-path', dirname(__FILE__) . '/config/routes.php');

//Process the request.
include $framework_install_path . '/run.php';

//Display the view.
print $Template->display();
