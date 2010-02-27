<?php
/**
 * The command to run the tests should look like:
 *
 * phpunit --bootstrap='bootstrap.php' --colors .
 *
 * When running from the tests directory.
 */
date_default_timezone_set('UTC');

if (substr(phpversion(), 0, 3) != '5.2') {
  error_reporting(E_ALL ^ E_DEPRECATED ^ E_NOTICE);
}

include '../paths.php';
