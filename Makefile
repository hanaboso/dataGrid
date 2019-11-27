.PHONY: init-dev

DC=docker-compose
DE=docker-compose exec -T php
DM=docker-compose exec -T mariadb

.env:
	sed -e "s|{DEV_UID}|$(shell id -u)|g" \
		-e "s|{DEV_GID}|$(shell id -u)|g" \
		-e "s/{SSH_AUTH}/$(shell if [ "$(shell uname)" = "Linux" ]; then echo "\/tmp\/.ssh-auth-sock"; else echo '\/tmp\/.nope'; fi)/g" \
		.env.dist >> .env;

# Docker
docker-up-force: .env
	$(DC) pull
	$(DC) up -d --force-recreate --remove-orphans

docker-down-clean: .env
	$(DC) down -v

# Composer
composer-install:
	$(DE) composer install --ignore-platform-reqs

composer-update:
	$(DE) composer update --ignore-platform-reqs

composer-outdated:
	$(DE) composer outdated

# Console
clear-cache:
	$(DE) rm -rf temp

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
	$(DE) ./vendor/bin/phpunit  -c ./vendor/hanaboso/php-check-utils/phpunit.xml.dist tests/Integration

test: docker-up-force composer-install fasttest

fasttest: clear-cache codesniffer phpstan phpintegration
