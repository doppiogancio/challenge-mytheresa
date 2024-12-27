build:
	docker compose build

setup:
	make build start recreate-database

tests:
	docker compose exec php ./vendor/bin/phpunit

recreate-database:
	docker compose exec php ./bin/console doctrine:database:drop --if-exists --force && \
	docker compose exec php ./bin/console doctrine:database:create && \
	docker compose exec php ./bin/console doctrine:migrations:migrate --no-interaction && \
	docker compose exec php ./bin/console doctrine:fixtures:load --no-interaction --group=api

recreate-database-large:
	docker compose exec php ./bin/console doctrine:database:drop --if-exists --force && \
	docker compose exec php ./bin/console doctrine:database:create && \
	docker compose exec php ./bin/console doctrine:migrations:migrate --no-interaction && \
	docker compose exec php ./bin/console doctrine:fixtures:load --no-interaction --group=large

start:
	docker compose up -d

start-dev:
	docker compose -f compose.yml -f compose.dev.yml up

coverage:
	docker compose exec php XDEBUG_MODE=coverage ./vendor/bin/phpunit --coverage-html public/coverage --coverage-filter src

stop:
	docker compose down

logs:
	docker compose logs -f

cli:
	docker compose exec php bash

clear-symfony-cache:
	docker compose exec php ./bin/console c:c

clear-redis-cache:
	docker compose exec cache redis-cli -h cache flushall

clear-cache:
	make clear-symfony-cache clear-redis-cache

create-certs:
	openssl req -newkey rsa:4096 -x509 -sha256 -nodes -out my.crt -keyout my.key -addext "subjectAltName=DNS:chronos.luxury" -subj "/O=The company/CN=chronos.luxury"
