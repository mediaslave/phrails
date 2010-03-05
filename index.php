<?php
$start = (float) array_sum(explode(' ',microtime()));

error_reporting(E_ALL);

/*
 * Changes this to where you have the framework installed.
 * This allows you to share one fromework for multiple projects.
 * Move the framework folder where ever you want, just change this var.
*/
$framework_install_path = 'framework';

//include the framework paths
include $framework_install_path . '/paths.php';

//Load the environment
include 'config/environment.php';

//Process the request.
include $framework_install_path . '/run.php';

//Display the view.
print $Template->display();

$end = (float) array_sum(explode(' ',microtime()));

echo "Processing time: ". sprintf("%.4f", ($end-$start))." seconds";