all: deps_dev test

## Run tests - frontend and backend
test: test_be test_fe

test_be: deps_be
	php vendor/bin/phpunit test/Unit/

test_fe: deps_fe
	echo "WHY U NO TEST?"

deps_be: composer.phar
	./composer.phar install \
		--no-progress \
		--prefer-dist \
		--optimize-autoloader \
		--no-interaction

deps_fe:
	exit 0

## Makefile deps
composer.phar:
	php -r "readfile('https://getcomposer.org/installer');" | php
	chmod +x composer.phar

## Print this help
help:
	@awk -v skip=1 \
		'/^##/ { sub(/^[#[:blank:]]*/, "", $$0); doc_h=$$0; doc=""; skip=0; next } \
		 skip  { next } \
		 /^#/  { doc=doc "\n" substr($$0, 2); next } \
		 /:/   { sub(/:.*/, "", $$0); printf "\033[34m%-30s\033[0m\033[1m%s\033[0m %s\n\n", $$0, doc_h, doc; skip=1 }' \
		$(MAKEFILE_LIST)
