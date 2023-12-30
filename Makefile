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
