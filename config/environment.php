<?php

//Set up the include paths for the app.
set_include_path(get_include_path() . 
	 PATH_SEPARATOR . './app/controllers' . 
	 PATH_SEPARATOR . './app/models' . 
	 PATH_SEPARATOR . './app/views'. 
	 PATH_SEPARATOR . './lib');

//load the correct files specified by the environment.
if(!isset($_SERVER['PHP_RAILS_ENV'])){
	include 'environments/development.php';
}else{
	include 'environments/' . $_SERVER['PHP_RAILS_ENV'] . '.php';
}