<?php
stylesheet_link_tag('screen', 'media:all')
//Generates: <link href="/public/stylesheets/screen.css" media="all" type="text/css" />

//Link to a css file in a sub folder
stylesheet_link_tag('sub/folder/screen');
//Generates: <link href="/public/stylesheets/sub/folder/screen.css" type="text/css" />