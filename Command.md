

# Liste des commandes pour crée le projet symfony :

```bash

symfony new forge-de-heros --webapp
cd forge-de-heros

cp .env .env.local

symfony console doctrine:database:create

symfony console make:entity Race
# Champs: name (string), description (text)

symfony console make:entity CharacterClass
# Champs: name (string), description (text), healthDice (integer)

symfony console make:entity Skill
# Champs: name (string), ability (string : STR/DEX/CON/INT/WIS/CHA)

symfony console make:entity Character
# Champs: name, level, strength, dexterity, constitution, intelligence, wisdom, charisma, healthPoints, image (string)

composer require symfony/security-bundle

symfony console make:entity Party
# Champs: name (string), description (text), maxSize (integer)



# Ca crée des fichiers automatiquement, voir paramètres (normalement si j'ai bien fais les truc ça devrait être bon, sinon il faudra faire les relations à la main dans les fichiers d'entités)
symfony console make:user
# Nom : User, stocké en BDD : oui, identifiant : email, hashage : oui

symfony console make:security:form-login
# Génère le controller de login + security.yaml

symfony console make:registration-form
# Email de vérification : Non


symfony console make:entity User #Crée le champ username (string) dans l'entité User (pas crée automatiquement malheureusement)
# Ajouter le champ username (string)


symfony console make:migration
symfony console doctrine:migrations:migrate


symfony console make:crud Race
symfony console make:crud CharacterClass
symfony console make:crud Skill
symfony console make:crud Character
symfony console make:crud Party


#installer les fixtures pour les tests
composer require --dev orm-fixtures

symfony console doctrine:fixtures:load # les charger

composer require nelmio/cors-bundle # installer NelmioCorsBundle 
```



# Liste des commandes pour crée le projet React:

```bash

npm create vite@latest forge-de-heros-front -- --template react

```


Configuration du projet symfony et React:

Création du projet Symfony,  configuration la base de données, création des entités, migration des données, génération des crud, installation des fix, installation de NelmioCorsBundle
Configuration du template React.