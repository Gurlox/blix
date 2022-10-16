Set up:
- docker-compose build
- docker-compose up -d
- docker exec -it php bash
- composer install
- composer init

Tests:
- php bin/phpunit tests/functional
- php bin/phpunit tests/unit

URL:
- form: localhost:8003
- swagger: localhost:8003/api/doc

CLI:
- php bin/console app:create-post title text imagePath
