<?php
$Route = Registry::get('pr-route');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">

<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>No Respond To View Type</title>
	<meta name="author" content="Justin Palmer">
</head>
<body>
<h1>No Respond to View Type</h1>

<p>We could not verify your respond to type.</p>
	
<p><?= $Route->requested?></p>
</body>
</html>
