<?php
//Set the domain that this app is running on.
Registry::set('pr-domain-uri', 'http://php-rails');

//Set where the app is installed.
//  '/' represents the application being installed on the root.
Registry::set('pr-install-path', '/');

//Set the session save handler here.
//Not implemented yet.
//Registry::set('pr-session-handler', 'file');
//Registry::set('pr-session-handler', 'database');


//Do you want to cache the template in this environment?
Registry::set('pr-cache-template', null);