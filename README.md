# Gestion de documents en PHP/Symfony

## Installation :

```bash
docker-compose up -d
docker exec -it -u www-data web php bin/console doctrine:schema:create --no-interaction
docker exec -it -u www-data web php bin/console doctrine:fixtures:load --no-interaction
```
