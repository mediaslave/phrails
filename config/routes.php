<?php
/*
 * Example routes.
 */
$Routes->add('edit-profile', '/profile/{id}/edit', 'Profile', 'edit');

$Routes->add('world', '/world', 'Home', 'world');

$Routes->root('/', 'Home', 'index');

$Routes->resources('student', 'Student');
