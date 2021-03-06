SHELL=/bin/bash -e

.DEFAULT_GOAL := help

COMPOSE_PROJECT_NAME="hitfly"
VIRTUAL_HOST="hitfly.local"

help: ## This help
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

migrate: ## run migration
	@docker exec -i hitfly_php sudo -u www-data php artisan migrate --force

build: ## Билд проекта
	@docker-compose build --build-arg user_uid=$$(id -u)

composer: ## composer install
	@docker exec -i hitfly_php sudo -u www-data composer install --dev

npm: ## npm install
	npm i
	cd ssr && npm i

app-install: up composer npm ## Install

up: ## start project
	@docker-compose up -d

down: ## stop project
	@docker-compose down

restart: down up ## Restart project

code-style: ## Код сатйл
	@composer fix

submodule-update:
	git submodule update --remote --force  --rebase

cache: ## clear cache
	@docker exec -it $(COMPOSE_PROJECT_NAME)_php sudo -u www-data php artisan config:clear
#	@docker exec -it $(COMPOSE_PROJECT_NAME)_php sudo -u www-data php artisan config:cache

bash: ## Run bash ssh php continer
	@docker exec -it $(COMPOSE_PROJECT_NAME)_php bash

socket: ## Run socket
	@docker exec $(COMPOSE_PROJECT_NAME)_php sudo -u www-data php artisan workman start --d

seed: ## run seeder
	docker exec -it $(COMPOSE_PROJECT_NAME)_php sudo -u www-data php artisan db:seed

logs: ## Show docker containers logs
	@docker-compose logs --follow

testing: ## run tests
	@docker exec -it $(COMPOSE_PROJECT_NAME)_php sudo -u www-data vendor/bin/phpunit
