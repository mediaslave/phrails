<?php
$Route = Registry::get('pr-route');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">

<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>View Not Found</title>
	<meta name="author" content="Justin Palmer">
</head>
<body>
<h1>View Not Found</h1>

</p>We could not find the view: <b>app/views/<?= $Route->requested ?></b></p>
	
<p>Create the view at <em>app/views/<?= $Route->requested?></em></p>
</body>
</html>