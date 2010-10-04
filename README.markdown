#Create a Project

To create a new project using phrails please go to the phrails-bin project:

	http://github.com/mediaslave/phrails-bin

#Apache

Under Apache, add the following to virtual host:

	<VirtualHost *:80>
	    ServerName php-rails
	    DocumentRoot /home/jpalmer/projects/php/phrails
		
        setenv PHRAILS_ENV development
        setenv PHRAILS_INSTALL_PATH /

	    RewriteEngine off

	    <Location /app>
	        Deny from all                
	    </Location>

	    <Location /config>
	        Deny from all
	    </Location>

	    <Location /db>
	        Deny from all
	    </Location>
	
	    <Location /framework>
	        Deny from all
	    </Location>

	    <Location /lib>
	        Deny from all
	    </Location>

	    <Location /script>
	        Deny from all
	    </Location>
	
	    <Location /test>
	        Deny from all
	    </Location>
	
	    <Location /tmp>
	        Deny from all
	    </Location>
	
	    <Location /vendor>
	        Deny from all
	    </Location>
	
	    <Location />
	        RewriteEngine on      
	        RewriteCond %{REQUEST_FILENAME} !-f
	        RewriteCond %{REQUEST_FILENAME} !-d
	        RewriteRule !\.(js|ico|gif|jpg|png|css)$ /index.php
	    </Location>

	</VirtualHost>
	
# Install in subdirectory 

Create a .htaccess file in:
	app
	config
	db
	lib
	script
	test
	tmp
	vendor
		
The .htaccess should have 'deny from all' in the file.
	
In the base of the app (example: /blog/) put:
	
	#setenv is only supported in apache 1.3.7
	#within in a .htaccess file
	setenv PHRAILS_ENV development
	setenv PHRAILS_BASE_URI /path/to/sub/directory/install/

	RewriteEngine On

	Options Indexes FollowSymLinks

	RewriteBase /path/to/sub/directory/install/
	RewriteCond %{REQUEST_FILENAME} !-f
	RewriteCond %{REQUEST_FILENAME} !-d
	RewriteRule !\.(js|ico|gif|jpg|png|css)$ index.php

# Generate controllers and models
	
Controller generator will create the controller class and the view files based off the actions provided.
	
	Format: script/generate controller ControllerName [action, ...]
	
	Example: script/generate controller Home
	Example: script/generate controller Home index about

Model generator will create the correct model class.  Singular model names expect plural table names.
	
	Format: script/generate model ModelName
	
	Example: script/generate model User

# Docs

To create documentation from the base folder run:

	phpdoc -t docs -d . -ti Phrails -o HTML:frames:earthli -i .git/,tests/,*examples/,__view__/
	
#Tests

To run tests from the tests folder in the base folder run:

	phpunit --bootstrap bootstrap.php --colors . 
	
#Continuous Integration with Hudson

We have set up CI using Hudson.  We will be adding other projects here that are related to Phrails (plugins, cmd tools, etc...).

It can be found here:

	http://173.203.202.197:8080/