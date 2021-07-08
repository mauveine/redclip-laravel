# Redclip API

## Project setup
```
composer install
cp .env-example .env
php artisan key:generate
php artisan migrate:fresh --seed
```
Change in the .env file the api url. If you're using laravel artisan serve, put the `http://localhost:<servePort>`

## Localhost env
To run the server in localhost run
`php artisan serve`