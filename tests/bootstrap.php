<?php
/**
 * * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
 * @package tests
 */
/**
 * Description
 *
 * @package tests
 * @author Justin Palmer
 */
//Running PHPUnit 3.5.15 this is to fix bug
require_once('PHP/Token/Stream/Autoload.php');

date_default_timezone_set('UTC');

if (substr(phpversion(), 0, 3) != '5.2') {
  error_reporting(E_ALL ^ E_DEPRECATED ^ E_NOTICE);
}

include __DIR__ . '/../paths.php';
