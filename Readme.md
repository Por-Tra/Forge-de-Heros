

## Configuration du projet Forge des Héros

Lancer les commandes suivantes pour configurer le projet :

```bash

composer install
cp .env.example .env

symfony console doctrine:migrations:migrate

```

Lancer les commandes suivantes pour lancer le projet :

```bash
symfony server:start
```

