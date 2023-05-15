DOCKER_COMPOSE = docker compose
EXEC_PHP = $(DOCKER_COMPOSE) exec -T php
EXEC_SYMFONY = $(DOCKER_COMPOSE) exec -T php bin/console
EXEC_DB = $(DOCKER_COMPOSE) exec -T database sh -c
QUALITY_ASSURANCE = $(DOCKER_COMPOSE) run --rm quality-assurance
COMPOSER = $(EXEC_PHP) composer

#################################
Docker:

pull: docker-compose.yml
	@echo "\nPulling local images...\e[0m"
	@$(DOCKER_COMPOSE) pull --quiet

build: docker-compose.yml pull ##Build docker
	@echo "\nBuilding local images...\e[0m"
	@$(DOCKER_COMPOSE) build

## Up environment
up: docker-compose.yml ##Up docker
	@$(DOCKER_COMPOSE) up -d --remove-orphans

## Up environment with rebuild
up-recreate: docker-compose.yml ##Up docker
	@$(DOCKER_COMPOSE) up -d --build --force-recreate --remove-orphans

## Down environment
down: docker-compose.yml ##Down docker
	@$(DOCKER_COMPOSE) kill
	@$(DOCKER_COMPOSE) down --remove-orphans

## View output from all containers
logs: docker-compose.yml ##Logs from docker
	@${DOCKER_COMPOSE} logs -f --tail 0

.PHONY: pull build up down logs

#################################
Project:

## Up the project and load database
install: build up vendor db-load-fixtures

## Reset the project
reset: down install

## Start containers (unpause)
start: docker-compose.yml
	@$(DOCKER_COMPOSE) unpause || true
	@$(DOCKER_COMPOSE) start || true

##Stop containers (pause)
stop: docker-compose.yml
	@$(DOCKER_COMPOSE) pause || true

##Install composer
vendor: codebase/composer.lock
	@echo "\nInstalling composer packages...\e[0m"
	@$(COMPOSER) install

## Update composer
composer-update: codebase/composer.json
	@echo "\nUpdating composer packages...\e[0m"
	@$(COMPOSER) update

## Install package with composer
composer-require: codebase/composer.json
	@echo "\nUpdating composer packages...\e[0m"
	@$(COMPOSER) require $(P)

## Install package with composer for dev
composer-require-dev: codebase/composer.json
	@echo "\nUpdating composer packages...\e[0m"
	@$(COMPOSER) require --dev $(P)

## Clear symfony cache
cc:
	@echo "\nClearing cache...\e[0m"
	@$(EXEC_SYMFONY) c:c
	@$(EXEC_SYMFONY) cache:pool:clear cache.global_clearer

wait-db:
	@echo "\nWaiting for DB...\e[0m"
	@$(EXEC_PHP) php -r "set_time_limit(60);for(;;){if(@fsockopen('database',3306))die;echo \"\";sleep(1);}"

.PHONY: install reset start stop vendor composer-update node-modules wait-db cc

#################################
Database:

## Recreate database structure
db-reload-schema: wait-db db-drop db-create db-update

## Create database
db-create: wait-db
	@echo "\nCreating database...\e[0m"
	@$(EXEC_SYMFONY) doctrine:database:create --if-not-exists

## Create database
db-update:
	@echo "\nUpdate database...\e[0m"
	@$(EXEC_SYMFONY) doctrine:schema:update --force

## Drop database
db-drop: wait-db
	@echo "\nDropping database...\e[0m"
	@$(EXEC_SYMFONY) doctrine:database:drop --force --if-exists

## Generate migration by diff
db-diff: wait-db
	$(EXEC_SYMFONY) doctrine:migration:diff --formatted --allow-empty-diff

## Load migration
db-migrate: wait-db
	@echo "\nRunning migrations...\e[0m"
	@$(EXEC_SYMFONY) doctrine:migration:migrate --no-interaction --all-or-nothing

## Reload fixtures
db-load-fixtures: wait-db db-reload-schema
	@echo "\nLoading fixtures from fixtures files...\e[0m"
	@$(EXEC_SYMFONY) doctrine:fixtures:load --no-interaction
