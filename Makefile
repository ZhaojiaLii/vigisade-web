
.PHONY: init-db
init-db:
	php bin/console doctrine:database:drop --force
	php bin/console doctrine:database:create
	php bin/console doctrine:migrations:migrate

.PHONY: composer-install
composer-install:
	composer install
