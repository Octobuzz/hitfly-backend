SHELL=/bin/bash -e

.DEFAULT_GOAL := help

COMPOSE_PROJECT_NAME="hitfly"
VIRTUAL_HOST="hitfly.local"

help: ## This help
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

migrate: ## run migration
	@docker exec -i hitfly_php sudo -u www-data php artisan migrate --force

build: ## Билд проекта
	@docker-compose -p ${COMPOSE_PROJECT_NAME} build --build-arg user_uid=$$(id -u)

composer: ## composer install
	@docker exec -i hitfly_php sudo -u www-data composer install --dev

npm: ## npm install
	@npm i -D
	cd ssr && npm install

app-install: up composer npm ## Install

up: ## start project
	@docker-compose up -d

down: ## stop project
	@docker-compose down --remove-orphans

restart: down up ## Restart project

code-style: ## Код сатйл
	@composer fix

submodule-update:
	git submodule update --remote

cache: ## clear cache
	@docker exec -it $(COMPOSE_PROJECT_NAME)_php sudo -u www-data php artisan config:clear
	@docker exec -it $(COMPOSE_PROJECT_NAME)_php sudo -u www-data php artisan config:cache

bash: ## Run bash ssh php continer
	@docker exec -it $(COMPOSE_PROJECT_NAME)_php bash

socket: ## Run socket
	@docker exec $(COMPOSE_PROJECT_NAME)_php sudo -u www-data php artisan workman start --d

seed: ## run seeder
	docker exec -it $(COMPOSE_PROJECT_NAME)_php sudo -u www-data php artisan db:seed
