# Forge des Héros

<p align="center">
  <img src="https://img.shields.io/badge/Symfony-6.x-black?logo=symfony">
  <img src="https://img.shields.io/badge/React-18-blue?logo=react">
  <img src="https://img.shields.io/badge/SQLite-Database-lightgrey?logo=sqlite">
  <img src="https://img.shields.io/badge/API-REST-green">
  <img src="https://img.shields.io/github/license/Por-Tra/Forge-de-Heros">
</p>

<p align="center">
  Application web de création et gestion de personnages de jeu de rôle<br>
  Inspirée de l'univers de Donjons & Dragons
</p>

---

## Présentation

Forge des Héros est une application web permettant de créer, gérer et explorer des personnages de jeu de rôle.

Le projet est structuré en deux applications distinctes :

- Une application Symfony (backend + API REST)
- Une application React (interface utilisateur)

L'application permet de gérer des personnages, leurs caractéristiques, leurs classes ainsi que leur intégration dans des groupes d’aventure.

---

## Fonctionnalités

### Gestion des utilisateurs

- Inscription et authentification
- Gestion des rôles (administrateur / utilisateur)
- Accès restreint aux fonctionnalités selon les permissions

### Gestion des personnages

- Création, modification et suppression de personnages
- Attribution d'une race et d'une classe
- Système de caractéristiques (STR, DEX, CON, INT, WIS, CHA)
- Upload d’image (avatar)
- Calcul automatique des points de vie
- Système de répartition de points (Point Buy)

### Classes et compétences

- Association de compétences aux classes
- Gestion des relations entre classes et compétences

### Groupes d’aventure

- Création de groupes (Party)
- Gestion du nombre maximum de membres
- Ajout et retrait de personnages

### Recherche et filtrage

- Recherche de personnages par nom
- Filtrage par race et classe
- Filtrage des groupes (complets ou disponibles)

### API REST

- Exposition des données via une API publique
- Endpoints pour races, classes, compétences, personnages et groupes
- Réponses JSON structurées

### Interface React

- Affichage des personnages sous forme de cartes
- Navigation entre les pages
- Filtres dynamiques
- Pages de détail pour personnages et groupes
- Visualisation des statistiques

---

## Stack technique

### Backend

- Symfony
- Doctrine ORM
- SQLite
- API REST
- NelmioCorsBundle

### Frontend

- React (Vite)
- Fetch API

---

## Installation

### Backend Symfony

```bash
git clone https://github.com/Por-Tra/Forge-de-Heros.git
cd forge-des-heros-API

composer install
cp .env.local .env

symfony console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load
```

### Surcharcger le fichier .env.local avec les informations de connexion à la base de données SQLite :

```bash
DATABASE_URL="sqlite:///%kernel.project_dir%/var/data.db"
```

### Lancement du serveur

```bash
symfony server:start
```

---

### Frontend React

```bash
cd forge-de-heros-front-React

npm install
npm run dev
```

---

## API

Base URL :

```
http://localhost:8000/api/v1
```

### Routes principales

| Route            | Description           | Accessibilité         |
| ---------------- | --------------------- |                       |
| /race            | Liste des races       | Admin                 |
| /character/class | Liste des classes     | Admin                 |
| /skills          | Liste des compétences | Admin                 |
| /character       | Liste des personnages | User                  |
| /party           | Liste des groupes     | User                  |



---

## Logique métier

### Système de caractéristiques

Chaque personnage possède six caractéristiques :

- Strength
- Dexterity
- Constitution
- Intelligence
- Wisdom
- Charisma

Contraintes :

- Valeur minimale : 8
- Valeur maximale : 15
- Budget total : 27 points

---

### Calcul des points de vie

Les points de vie sont calculés automatiquement :

```
HP = dice_max + floor((constitution - 10) / 2)
```

---

## Structure du projet

```
/forge-des-heros-API
 ├── src/
 ├── migrations/
 ├── fixtures/
 └── config/

/forge-de-heros-front-React
 ├── src/
 ├── components/
 └── pages/
```

---

## Contributeurs

<p align="center">
  <a href="https://github.com/Por-Tra">Por-Tra</a> •
  <a href="https://github.com/loazur">loazur</a> •
  <a href="https://github.com/LucasChap-git">LucasChap</a> •
  <a href="https://github.com/Corentino74">Corentino74</a> •
  <a href="https://github.com/Mortann">Mortann</a>
</p>

## Notes

- Utilisation de SQLite
- Chargement des fixtures requis avant utilisation
- Configuration CORS nécessaire pour la communication entre le frontend et l’API

---

## Licence

Ce projet est open-source.
