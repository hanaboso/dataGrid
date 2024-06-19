.PHONY: init-dev test

DC=docker-compose
DE=docker-compose exec -T php
DM=docker-compose exec -T mariadb

.env:
	sed -e "s/{DEV_UID}/$(shell if [ "$(shell uname)" = "Linux" ]; then echo $(shell id -u); else echo '1001'; fi)/g" \
		-e "s/{DEV_GID}/$(shell if [ "$(shell uname)" = "Linux" ]; then echo $(shell id -g); else echo '1001'; fi)/g" \
		.env.dist > .env; \

# Docker
docker-up-force: .env
	$(DC) pull
	$(DC) up -d --force-recreate --remove-orphans

docker-down-clean: .env
	$(DC) down -v

# Composer
composer-install:
	$(DE) composer install
	$(DE) composer update --dry-run roave/security-advisories

composer-update:
	$(DE) composer update
	$(DE) composer update --dry-run roave/security-advisories
	$(DE) composer normalize

composer-outdated:
	$(DE) composer outdated

# Console
clear-cache:
	$(DE) rm -rf var

# App dev
init-dev: docker-up-force composer-install

database-create:
	$(DM) bin/bash -c 'while ! mysql -uroot -proot <<< "DROP DATABASE IF EXISTS datagrid;" > /dev/null 2>&1; do sleep 1; done'
	$(DM) bin/bash -c 'mysql -uroot -proot <<< "DROP DATABASE IF EXISTS datagrid;"'
	$(DM) bin/bash -c 'mysql -uroot -proot <<< "DROP DATABASE IF EXISTS datagrid1;"'
	$(DM) bin/bash -c 'mysql -uroot -proot <<< "DROP DATABASE IF EXISTS datagrid2;"'
	$(DM) bin/bash -c 'mysql -uroot -proot <<< "CREATE DATABASE datagrid;"'
	$(DM) bin/bash -c 'mysql -uroot -proot <<< "CREATE DATABASE datagrid1;"'
	$(DM) bin/bash -c 'mysql -uroot -proot <<< "CREATE DATABASE datagrid2;"'

phpcodesniffer:
	$(DE) ./vendor/bin/phpcs --parallel=$$(nproc) --standard=./ruleset.xml src tests

phpcodesnifferfix:
	$(DE) vendor/bin/phpcbf --parallel=$$(nproc) --standard=./ruleset.xml src tests

phpstan:
	$(DE) ./vendor/bin/phpstan analyse -c ./phpstan.neon -l 8 src tests

phpintegration: database-create
	$(DE) ./vendor/bin/paratest -c ./vendor/hanaboso/php-check-utils/phpunit.xml.dist -p 1 tests/Integration

phpcoverage:
	$(DE) ./vendor/bin/paratest -c ./vendor/hanaboso/php-check-utils/phpunit.xml.dist -p $$(nproc) --coverage-html var/coverage --cache-directory var/cache/coverage --coverage-filter src tests

phpcoverage-ci:
	$(DE) ./vendor/hanaboso/php-check-utils/bin/coverage.sh -c 98

test: docker-up-force composer-install fasttest

fasttest: clear-cache phpcodesniffer phpstan phpintegration phpcoverage-ci
