# PHP learning project "Explore space"

## Project setup
```
cd ./Docker
sudo cp .env.example .env
docker-compose build
docker-compose up -d
docker-compose run app composer install
```
 For upload data to the DB run  backend\sql\script.sql