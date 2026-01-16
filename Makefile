DOCKER_COMPOSE = docker compose
PHP_CONT = php

.PHONY: up down build install test phpstan rector-dry-run rector-fix

up:
	$(DOCKER_COMPOSE) up -d

down:
	$(DOCKER_COMPOSE) down

build:
	$(DOCKER_COMPOSE) build

install: build up
	$(DOCKER_COMPOSE) exec $(PHP_CONT) composer install

test:
	$(DOCKER_COMPOSE) exec $(PHP_CONT) bin/phpunit

phpstan:
	$(DOCKER_COMPOSE) exec $(PHP_CONT) vendor/bin/phpstan analyse

rector-lint:
	$(DOCKER_COMPOSE) exec $(PHP_CONT) vendor/bin/rector process --dry-run

rector-fix:
	$(DOCKER_COMPOSE) exec $(PHP_CONT) vendor/bin/rector process
