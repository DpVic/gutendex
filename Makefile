DOCKER_COMPOSE = docker compose
PHP_CONT = php

.PHONY: up down build install test behat phpstan rector-lint rector-fix cs-lint cs-fix pre-push shell cache-clear

up:
	$(DOCKER_COMPOSE) up -d

down:
	$(DOCKER_COMPOSE) down

build:
	$(DOCKER_COMPOSE) build

install: build up
	$(DOCKER_COMPOSE) exec $(PHP_CONT) composer install
	mkdir -p .git/hooks
	cp scripts/pre-push .git/hooks/pre-push
	chmod +x .git/hooks/pre-push

shell:
	$(DOCKER_COMPOSE) exec $(PHP_CONT) bash

test:
	$(DOCKER_COMPOSE) exec -T $(PHP_CONT) env APP_ENV=test bin/phpunit;

behat:
	$(DOCKER_COMPOSE) exec -T $(PHP_CONT) env APP_ENV=test vendor/bin/behat;

pre-push:
	sh scripts/pre-push

phpstan:
	$(DOCKER_COMPOSE) exec -T $(PHP_CONT) vendor/bin/phpstan analyse --memory-limit=1G

rector-lint:
	$(DOCKER_COMPOSE) exec -T $(PHP_CONT) vendor/bin/rector process --dry-run

rector-fix:
	$(DOCKER_COMPOSE) exec $(PHP_CONT) vendor/bin/rector process

cs-lint:
	$(DOCKER_COMPOSE) exec -T $(PHP_CONT) vendor/bin/php-cs-fixer fix --dry-run --diff

cs-fix:
	$(DOCKER_COMPOSE) exec $(PHP_CONT) vendor/bin/php-cs-fixer fix

cache-clear:
	$(DOCKER_COMPOSE) exec $(PHP_CONT) bin/console cache:clear
