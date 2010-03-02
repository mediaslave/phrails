<?php
//Set the domain that this app is running on.
Registry::set('pr-domain-uri', '');

//Set where the app is installed.
//  '/' represents the application being installed on the root.
Registry::set('pr-install-path', '/');

//Set the session save handler here
//Registry::set('pr-session-handler', 'file');
//Registry::set('pr-session-handler', 'database');