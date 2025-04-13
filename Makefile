lifetime?=1
dc_conf=-f docker-compose.yml --env-file .env
project_name=-p $(shell basename $(CURDIR))

.PHONY: default
default: help

.PHONY: help
help: ## This help
	@echo 'Available "make" commands:'
	@grep -E '^[a-zA-Z_-]+:(.*?## .*)*$$' $(MAKEFILE_LIST) | sed -e 's/Makefile://' -e 's/:/##/g' | sort | awk 'BEGIN {FS = "##"}; {printf "\033[36m%-30s\033[0m %-30s %s\n", $$1, $$3,$$4}'
	@echo
	@echo 'Run "make help" to see this doc'
	@echo


.PHONY: dump-autoload
dump-autoload: ## Fix the code style using php-cs-fixer
	docker compose $(dc_conf) $(project_name) exec app composer dump-autoload

.PHONY: phpstan
phpstan: ##Not using code analyzis as we have to analyse modules folder.
	docker compose $(dc_conf) $(project_name) exec app ./vendor/bin/phpstan analyze --memory-limit=2G

.PHONY: clean-code
clean-code: ## Fix the code style using php-cs-fixer
	docker compose $(dc_conf) $(project_name) exec app ./vendor/bin/pint

PHONY: ide-models
ide-models: ## Generate Ide helper file
	docker compose $(dc_conf) $(project_name) exec app php artisan  ide-helper:models -W -D app/Models
	docker compose $(dc_conf) $(project_name) exec app php artisan  ide-helper:models -W -D Modules/Blog/app/Models
	make clean-code

.PHONY: ide-help
ide-help: ## Generate Ide helper file
	docker compose $(dc_conf) $(project_name) exec app php artisan ide-helper:generate

.PHONY: migrate
migrate: ## Run migrations on the local php environment
	docker compose $(dc_conf) $(project_name) exec app php artisan migrate

.PHONY: reset-migrations
reset-migrations: ## Reset database
	docker compose $(dc_conf) $(project_name) exec app php artisan migrate:fresh

.PHONY: seeders
seeders: ## Seed the database
	docker compose $(dc_conf) $(project_name) exec app php artisan db:seed -v

.PHONY: tests
tests: ## Run tests
	docker compose $(dc_conf) $(project_name) exec app bash -c "php artisan test"

PHONY: test-coverage
test-coverage: ## Run tests coverage
	docker compose $(dc_conf) $(project_name) exec app bash -c "php -d pcov.enabled=1 -d pcov.directory=./ -d pcov.exclude=./vendor vendor/bin/phpunit --coverage-html public/coverage"


.PHONY: php
php: ## Run a shell on the local php environment
	docker compose $(dc_conf) $(project_name) exec app bash

.PHONY: mysql
mysql: ## Run a shell on the local mysql environment
	docker compose $(dc_conf) $(project_name) exec mysql bash

.PHONY: redis
redis: ## Run a shell on the local redis environment
	docker compose $(dc_conf) $(project_name) exec redis sh

.PHONY: logs
logs: ## Follow the local docker environment logs
	docker compose $(dc_conf) $(project_name) logs -f --tail=300

.PHONY: log-php
log-php: ## Follow the php container local docker environment logs
	docker compose $(dc_conf) $(project_name) logs -f --tail=300 app

.PHONY: build
build: ## Start the local docker environment
	docker compose $(dc_conf) $(project_name) build

.PHONY: down
down: ## Stop and remove the local docker environment
	docker compose $(dc_conf) $(project_name) down

.PHONY: up
up: ## Start the local docker environment
	docker compose $(dc_conf) $(project_name) up -d

.PHONY: restart
restart: ## Restart the local docker environment
	docker compose $(dc_conf) $(project_name) stop && docker compose $(dc_conf) $(project_name) up -d

.PHONY: reset
reset: down pull up ## Stop, remove, pull and start the local docker environment

.PHONY: ps
ps: ## Get the local docker environment status
	docker compose $(dc_conf) $(project_name) ps

.PHONY: pull
pull: ## Fetch docker images
	docker compose $(dc_conf) $(project_name) pull

.PHONY: laravel-install
laravel-install: ## Install Laravel
	docker compose $(dc_conf) $(project_name) exec app composer create-project --prefer-dist laravel/laravel . || :
	docker compose $(dc_conf) $(project_name) exec app composer require --dev barryvdh/laravel-ide-helper laravel/telescope phpstan/phpstan larastan/larastan phpstan/phpstan-mockery