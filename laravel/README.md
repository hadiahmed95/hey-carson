# Laravel

## Project setup
In the project root directory run
```
docker-compose up -d
```
```
cd laravel
composer install
```
php-version >= 8.1

Install Mysql
```
Create database app_heycarson;
```

Add .env file in laravel folder

Set the DB_USERNAME and DB_PASSWORD in the .env
```
DB_USERNAME=your-username
DB_PASSWORD=your-password
```

```
php artisan migrate 
php artisan db:seed 
php artisan serve --env=.env 
```
