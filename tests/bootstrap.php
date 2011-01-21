<?php
date_default_timezone_set('UTC');

if (substr(phpversion(), 0, 3) != '5.2') {
  error_reporting(E_ALL ^ E_DEPRECATED ^ E_NOTICE);
}

include __DIR__ . '/../paths.php';
