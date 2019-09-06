.PHONY: init-dev

IMAGE=dkr.hanaboso.net/hanaboso/datagrid/
PHP=dkr.hanaboso.net/hanaboso/php-base:php-7.3
DC=docker-compose
DE=docker-compose exec -T php
DM=docker-compose exec -T mariadb

.env:
	sed -e "s|{DEV_UID}|$(shell id -u)|g" \
		-e "s|{DEV_GID}|$(shell id -u)|g" \
		.env.dist >> .env;

# Docker
docker-up-force: .env
	$(DC) pull
	$(DC) up -d --force-recreate --remove-orphans

docker-down-clean: .env
	$(DC) down -v

dev-build: .env
	cd docker/php-dev && docker pull ${PHP} && docker build -t ${IMAGE}app:dev . && docker push ${IMAGE}app:dev

# Composer
composer-install:
	$(DE) composer install --ignore-platform-reqs

composer-update:
	$(DE) composer update --ignore-platform-reqs

composer-outdated:
	$(DE) composer outdated

# Console
clear-cache:
	$(DE) sudo rm -rf temp

# App dev
init-dev: docker-up-force composer-install

database-create:
	$(DM) bin/bash -c 'mysql -uroot -proot <<< "DROP DATABASE IF EXISTS datagrid;"'
	$(DM) bin/bash -c 'mysql -uroot -proot <<< "CREATE DATABASE datagrid;"'

codesniffer:
	$(DE) ./vendor/bin/phpcs --standard=./ruleset.xml --colors -p src/ tests/

phpstan:
	$(DE) ./vendor/bin/phpstan analyse -c ./phpstan.neon -l 7 src/ tests/

phpintegration: database-create
	$(DE) ./vendor/bin/phpunit -c phpunit.xml.dist --colors --stderr tests/Integration

test: docker-up-force composer-install fasttest

fasttest: clear-cache codesniffer phpstan phpintegration
