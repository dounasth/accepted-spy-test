USEFUL DOCKER COMMANDS

docker-compose up --build
docker-compose up --build --force-recreate
docker-compose up

docker-compose up --build -d
docker-compose up --build --force-recreate -d
docker-compose up -d

docker-compose down

docker-compose logs -f

docker-compose exec accepted-spy-test-app php artisan test
