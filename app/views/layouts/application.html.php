<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">

<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title><?=$title?></title>
	<meta name="author" content="Justin Palmer">
	<?=stylesheet_link_tag('screen', 'media:all')?>
</head>
<body>
	
	<?= flash_it($flash) ?><br/>
	<?=link_to('home', path('root'), 'class:menu')?>
	<br/>
	<?= $pr_view ?>
	<br/>
	
</body>
</html>
