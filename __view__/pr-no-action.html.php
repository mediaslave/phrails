<?php
$Route = Registry::get('pr-route');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">

<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Action Not Found</title>
	<meta name="author" content="Justin Palmer">
</head>
<body>
<h1>Action Not Found</h1>

<p>We could not find the Action: <b><?= $Route->no_controller . '#' . $Route->no_action ?></b></p>
	
<p>Create the action (method) in <em>app/controllers/<?= $Route->no_controller?>.php</em></p>
</body>
</html>
