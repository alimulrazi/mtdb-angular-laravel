# mtdb-angular-laravel application installation
Learn how to run MTDB angular and Laravel application

## Installation 
Make sure that you have setup the environment properly. You will need minimum PHP 8.2, MySQL/MariaDB, and composer.

Clone the repository-

```
https://github.com/alimulrazi/mtdb-angular-laravel.git
```

Then cd into the folder with this command-

```
cd mtdb-angular-laravel
```

Then do a composer install

```
composer install
```

1. Download the project (or clone using GIT)
2. Copy `.env.example` into `.env` and configure your database credentials
3. Go to the project's root directory using terminal window/command prompt
4. Run `composer install`
5. Set the application key by running `php artisan key:generate --ansi`
6. you can install application using the following url through installation window [http://localhost:8082/](http://localhost:8082/) Installation Window
7. Run migrations `php artisan migrate`
8. Start backend local server by executing `php artisan serve --port=8082`
9. Visit here for backend [http://localhost:4201/secure/auth/login](http://localhost:4201/secure/auth/login) to test the API
10. Start frontend server by executing `ng serve --proxy-config proxy.conf.json --host 0.0.0.0 --port 4201`
11. Visit here for frontend [http://localhost:4201/login](http://localhost:4201/login) to test the application

### Run the following command:
```
composer dump-autoload
```

OR

```
composer dump-autoload --ignore-platform-reqs
```
