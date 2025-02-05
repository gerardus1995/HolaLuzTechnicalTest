start:
	docker compose up --build -d
	docker exec -it activities_php composer install

migration:
	docker exec -it activities_php php bin/console doctrine:migrations:migrate

stop:
	docker compose stop

down:
	docker compose down --rmi all

ping-mysql:
	@docker exec activities_db mysqladmin --user=root --password=chopin --host "127.0.0.1" ping --silent

