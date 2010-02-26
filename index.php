<?php
//bring in the include paths needed.
//include 'framework/paths.php';
error_reporting(E_ALL);
//Process the request.
include 'framework/run.php';
//Display the view.
print $Template->display();