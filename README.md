# Gestion de documents en PHP/Symfony

## Installation :

```bash
docker-compose up -d
docker-compose run -u www-data web php bin/console doctrine:schema:create --no-interaction
docker-compose run -u www-data web php bin/console doctrine:fixtures:load --no-interaction
```
