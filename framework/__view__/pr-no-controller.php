<?php
$Route = Registry::get('pr-route');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">

<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Controller Not Found</title>
	<meta name="generator" content="TextMate http://macromates.com/">
	<meta name="author" content="Justin Palmer">
	<!-- Date: 2010-02-25 -->
</head>
<body>
</p>We could not find the controller: <b><?= $Route['requested-controller'] ?></b></p>
	
<p>Create the controller at <em>app/controllers/<?= $Route['requested-controller']?>.php</em></p>
</body>
</html>
