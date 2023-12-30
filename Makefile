start:
	docker-compose up -d

stop:
	docker-compose down

test:
	./vendor/bin/phpunit

build:
	make stop
	docker-compose build
	make start

init:
    cp .env.example .env
	composer install
	php artisan migrate
	make build

refresh_db:
    php artisan migrate:fresh
