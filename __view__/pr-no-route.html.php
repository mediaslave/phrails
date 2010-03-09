<?php
$Route = Registry::get('pr-route');
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
</p>We could not find the route: <b><?= $Route->requested ?></b></p>
	
<p>Add the route for <em><?= $Route->requested?></em> to config/routes.php</p>
</body>
</html>
