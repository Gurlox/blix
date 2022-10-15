- docker-compose build
- docker-compose up -d
- docker exec -it php bash
- composer init

Testy:
- php bin/phpunit tests/functional
- php bin/phpunit tests/unit

Adresy:
- formularz: localhost:8003
- swagger: localhost:8003/api/doc