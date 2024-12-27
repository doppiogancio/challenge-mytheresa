build:
	docker compose build

setup:
	make build start recreate-database logs

tests:
	docker compose exec php composer run tests

recreate-database:
	make clear-cache
	docker compose exec php ./bin/console doctrine:database:drop --if-exists --force && \
	docker compose exec php ./bin/console doctrine:database:create && \
	docker compose exec php ./bin/console doctrine:migrations:migrate --no-interaction && \
	docker compose exec php ./bin/console doctrine:fixtures:load --no-interaction --group=api

recreate-database-large:
	make clear-cache
	docker compose exec php ./bin/console doctrine:database:drop --if-exists --force && \
	docker compose exec php ./bin/console doctrine:database:create && \
	docker compose exec php ./bin/console doctrine:migrations:migrate --no-interaction && \
	docker compose exec php ./bin/console doctrine:fixtures:load --no-interaction --group=large

start:
	docker compose up -d

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
	docker compose exec redis redis-cli -h redis flushall

clear-cache:
	make clear-symfony-cache clear-redis-cache

cs-fix:
	docker compose exec php composer run cs-fix

phpstan:
	docker compose exec php composer run phpstan
