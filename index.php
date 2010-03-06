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

/**
 * If you would like to store plugins in a different directory than vendor
 * you can uncomment the line below and add the path to your plugins folder.
 * This allows you to share plugins over multiple projects and keep a 
 * cleaner source tree for this project
 */
//add_include_directory('/path/to/plugins/directory');

//Load the environment
include 'config/environment.php';

//Process the request.
include $framework_install_path . '/run.php';

//Display the view.
print $Template->display();

$end = (float) array_sum(explode(' ',microtime()));

echo "Processing time: ". sprintf("%.4f", ($end-$start))." seconds";