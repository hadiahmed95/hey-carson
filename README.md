# Setup project

## Docker Local Env

Before running docker compose make sure that all ports used in 
`docker-compose.local.yml` 
are available and not in use and then run 
`docker compose -f docker-compose.local.yml up -d` 
in the root project directory for local env


After docker finishes mounting images, you can run the laravel scripts:
```
docker exec api-app composer install
docker exec api-app cp .env.local .env
docker exec api-app php artisan key:generate
docker exec api-app php artisan migrate
docker exec api-app php artisan db:seed
docker exec api-app chmod 777 storage/ -R
```

You can use 
`docker rmi app-heycarson-vue-local` 
and 
`docker rmi app-heycarson-api-local` 
if you need to recreate docker images

Your application will be accessible on http://localhost:8080

## Docker Staging Env

You can also run 
`docker compose up -d` 
where you will make staging env running on your local machine. 
Note that you will be connected this way to staging backed and not the local one.

Your application will be accessible on http://localhost:80
## Running Without Docker

From base directory open terminal and run following:
- `cd laravel`
- `composer install`
- `cp .env.local .env` 
  - change MYSQL vars to match local mysql credentials
- `php artisan key:generate`
- `php artisan migrate`
- `php artisan db:seed`
- `php artisan serve --env=.env`
Open new terminal window and run following commands:
- `cd vue`
- `npm install`
- `npm run dev`

You can access application from the link provided in the terminal.

> It is possible to run all 3 instance of project on local env.