PHP=.bin/php -T
COMPOSER=.bin/composer -T --ansi

.PHONY: db-init
db-init:
	$(PHP) bin/console doctrine:database:drop --if-exists --force
	$(PHP) bin/console doctrine:database:create

.PHONY: db-prepare
db-prepare:
	$(PHP) bin/console make:migration
	make db-migrate

.PHONY: db-prepare-fixtures
db-prepare-fixtures: db-prepare
	$(PHP) bin/console doctrine:fixtures:load

.PHONY: db-migrate
db-migrate:
	$(PHP) bin/console doctrine:migrations:migrate

.PHONY: db-reset
db-reset-hard: db-init db-prepare-fixtures

.PHONY: assets-init
assets-init:
	$(PHP) bin/console assets:install --symlink

.PHONY: packages-init
packages-init:
	$(COMPOSER) install
