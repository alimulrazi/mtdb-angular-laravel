@echo off
echo Installing Swagger API Documentation...

echo Step 1: Installing composer dependencies...
composer require darkaonline/l5-swagger --ignore-platform-reqs

echo Step 2: Publishing Swagger configuration...
php artisan vendor:publish --provider "L5Swagger\L5SwaggerServiceProvider"

echo Step 3: Generating API documentation...
php artisan l5-swagger:generate

echo Step 4: Clearing cache...
php artisan config:clear
php artisan cache:clear
php artisan route:clear

echo Swagger installation completed!
echo You can access the API documentation at: http://localhost:8082/secure/documentation
pause