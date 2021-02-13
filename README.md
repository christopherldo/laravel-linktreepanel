# How to RUN

* ```mv .env.example .env``` to copy the env example to a real .env;
* Go to .env file and configure your database (DB section);
* ```composer install``` or ```php composer.phar install``` to install all needed libraries;
* ```php artisan migrate``` to create database tables and colums;
* ```php artisan key:generate``` to generate the application encryption key;
* ```php artisan serve``` to start the server;
