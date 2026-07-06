#seed addresses
mysql -u root -p < ph-address/location.sql

#Run this command whatever changes you have in config/pwa.php
php artisan erag:update-manifest

#To monitor performance using nightwatch, run this command:
php artisan nightwatch:agent
