<?php
$Route = Registry::get('pr-route');
$install_path = Registry::get('pr-install-path');

$route_requested = '/' . str_replace($install_path, '', $Route->requested);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">

<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Route Not Found</title>
	<meta name="author" content="Justin Palmer">
</head>
<body>
<h1>Route Not Found</h1>

<p><?= $Route->message ?></p>
	
<p>The request route was: <b><?= $route_requested ?></b></p>
	
<p>If the route that was closest does not match, please add the route <em><?= $route_requested ?></em> to config/routes.php</p>
</body>
</html>
