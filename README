To set it up, under Apache, add the following to virtual host:

<VirtualHost *:80>
    ServerName php-rails
    DocumentRoot /home/jpalmer/projects/php/php-rails

    setenv PHP_RAILS_ENV development
                
    RewriteEngine off
        
    <Location /app>
        Deny from all
    </Location>
        
    <Location /config>
        Deny from all
    </Location>
                
    <Location /framework>
        Deny from all
    </Location>
        
    <Location /lib>
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