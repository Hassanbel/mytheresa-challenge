# Mytheresa coding challenge
Having a Makefile, To use it run this command:

    make up

[Reference](/https://github.com/dunglas/symfony-docker/blob/main/docs/makefile.md)

# How to run the app and its tests if Maker file did work for you

After pulling the code from the repo, set the dotenv environment file from the example one:

    cp .env.example .env.local

    docker compose up -d --build

    docker compose exec php composer install

    docker compose exec -T php bin/console doctrine:database:create --if-not-exists

    docker compose exec -T php bin/console doctrine:migration:migrate --no-interaction --all-or-nothing
    
    docker compose exec -T php bin/console doctrine:fixtures:load --no-interaction

There you are:
- GET /products?category={category}&priceLessThan={price}: the main endpoint of the api


