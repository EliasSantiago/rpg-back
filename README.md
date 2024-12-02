# API RPG

API RPG.

## About the project

- Laravel 10.10
- Requires PHP >= 8.1
- Sqlite

## How to run the api?

**Step 1: Clone the project, run the following commands:**

**Step 2: Create file .env**
- cp .env.example .env
- Set APP_URL in .env: APP_URL=localhost:8000/api/
- Set database settings in .env: DB_CONNECTION=sqlite <br>

**Step 3: Run docker**
- docker-compose up -d

**Step 4: Install dependences:**
- docker-compose exec app composer install

**Step 5: Create Sqlite**
- docker exec -it rpg-app-1 /bin/bash
- touch database/database.sqlite
- To interact database with sql, run...
- sqlite3 /var/www/database/database.sqlite

**Step 6: Generate key in .env**
- in container run; php artisan key:generate

**Step 7: Install passport**
- in container run; php artisan passport:install

**Step 8: Generate tables**
- docker-compose exec app php artisan migrate

**Step 9: Seed the database**
- Após rodar as migrations, gere os dados iniciais para as classes de RPG com o seguinte comando:
- docker-compose exec app php artisan db:seed --class=RpgClassSeeder
- docker-compose exec app php artisan db:seed --class=UserSeeder
- docker-compose exec app php artisan db:seed --class=GuildSeeder

**Step 10: Generate/Update in the documentation**
- docker-compose exec app php artisan vendor:publish --provider "L5Swagger\L5SwaggerServiceProvider
- docker-compose exec app php artisan l5-swagger:generate

**Documentation API**
- http://localhost:8000/api/documentation

**PHP Code Analysis with Docker and PHPMD**
- docker run -it --rm -v $(pwd):/project -w /project jakzal/phpqa phpmd app text cleancode,codesize,controversial,design,naming,unusedcode

**Deploy config**
- The deploy settings can be changed in the .github directory.