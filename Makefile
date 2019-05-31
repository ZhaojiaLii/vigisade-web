PHP=.bin/php -T
COMPOSER=.bin/composer -T --ansi

.PHONY: init-db
init-db:
	$(PHP) bin/console doctrine:database:drop --if-exists --force
	$(PHP) bin/console doctrine:database:create
	$(PHP) bin/console doctrine:migrations:migrate

.PHONY: composer-install
composer-install:
	$(COMPOSER) install
