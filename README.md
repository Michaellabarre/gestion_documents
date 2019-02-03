# Gestion de documents en PHP/Symfony

## Installation :

```bash
docker-compose up -d
docker exec -it -u www-data web composer install
docker exec -it -u www-data web php bin/console doctrine:schema:create --no-interaction
docker exec -it -u www-data web php bin/console doctrine:fixtures:load --no-interaction
```

## Utilisateurs de test :
- Nom : user1/ Mot de passe : user1
- Nom : user2/ Mot de passe : user2
- Nom : user3/ Mot de passe : user3
- Nom : user4/ Mot de passe : user4
- Nom : user5/ Mot de passe : user5
