<p align="center"><img src="resources/imgs/CaMarcheLogo.svg" width="400" alt="Logo CaMarche"></p>

# 🥾 - Ca marche

## Table des Matières
- [🌐 - Contexte](#contexte--)
- [📊 - Description des Données](#description-des-données--)
- [🎯 - But du Projet](#but-du-projet--)
- [🔧 - Technologies Utilisées](#technologies-utilisées--)
- [⚙️ - Procédure d'installation](#procédure-d-installation--)
- [📎 - Wireframe & Maquette](#wireframe--maquette--)
- [📚 - Références](#références--)

## Contexte - 🌐

"Ca marche" est une web-application conçue pour découvrir les patrimoines culturels du canton de Vaud en Suisse. À travers cette application, les utilisateurs peuvent explorer des sentiers thématiques, découvrir des points d'intérêt et obtenir des informations pratiques sur les balades. L'objectif est de promouvoir le patrimoine local et d'encourager les résidents à découvrir la richesse culturelle de la région vaudoise.

## Description des Données - 📊

Les données utilisées dans cette application sont directement disponibles dans Laravel à travers des Seeders. Les Seeders peuplent la base de données avec les informations nécessaires pour faire fonctionner l'application. Voici les principales tables disponibles :

- **Users** : Contient les informations sur les utilisateurs de l'application, y compris leur nom, email, mot de passe, rôle et photo de profil.
- **Sentiers** : Stocke les informations sur les sentiers disponibles, comme le nom, la description, la durée, la longueur, le point de départ, le point d'arrivée, la photo et le thème associé.
- **Etapes** : Contient les informations sur les différentes étapes d'un sentier, telles que le nom, la description, la latitude, la longitude et l'ordre de l'étape.
- **Points d'intérêts** : Enregistre les points d'intérêt que les utilisateurs peuvent découvrir le long des étapes, incluant le nom, la description, la latitude, la longitude et la photo.
- **Themes** : Contient les thèmes prédominants associés aux sentiers, tels que "Nature", "Historique", etc.
- **Critères** : Stocke les critères spécifiques que les sentiers peuvent satisfaire, comme "Parking disponible" ou "Mobilité réduite".
- **Mots-clés** : Gère les mots-clés associés aux sentiers pour faciliter la recherche et la classification.
- **Commentaires** : Permet aux utilisateurs de laisser des avis sur les sentiers, chaque commentaire étant associé à un sentier spécifique.
- **Difficultes** : Permet de connaître le niveau de difficulté d'un sentier, pouvant aller du niveau facile au niveau difficile sous forme de graduation (1 ; 2 ; 3).

## But du Projet - 🎯

Le but de ce projet est de développer une web-application fonctionnelle qui permet de cartographier les sentiers disponibles dans la région vaudoise. Cette application vise à offrir aux utilisateurs une expérience interactive et informative, les invitant à se balader et à découvrir des points d'intérêt culturels. En facilitant l'accès à ces informations, "Ca marche" contribue à promouvoir le patrimoine local et à encourager les activités de plein air.

## Technologies Utilisées - 🔧

#### Vue
Vue.js est utilisé pour créer une interface utilisateur réactive et interactive. Il permet de développer des composants modulaires et réutilisables pour gérer les sentiers, les étapes, et les points d'intérêt.

#### Laravel
Laravel sert de back-end robuste et sécurisé. Il gère l'authentification des utilisateurs, les opérations CRUD sur les données, et les interactions avec la base de données via Eloquent ORM.

#### MapLibre
MapLibre est une bibliothèque open-source pour les cartes vectorielles, permettant de créer des cartes interactives et personnalisées.

#### MapTiler
MapTiler fournit des tuiles de cartes personnalisées basées sur OpenStreetMap, assurant des cartes de haute qualité et constamment mises à jour.

#### OpenRoute Service
OpenRoute Service est une API de calcul d'itinéraires, offrant des directions détaillées et optimisées pour différents modes de transport.

## Procédure d'installation - ⚙️

### Prérequis

Assurez-vous d'avoir les éléments suivants installés sur votre machine :

- PHP 8.3.3
- Composer
- MySQL 8.0
- MySQL Workbench
- Node.js
- NPM

### Installation de la Web-App sur votre machine locale 

#### A – Cloner le Repository GitHub

1. Ouvrez votre terminal ou ligne de commande.
2. Clonez le repository GitHub en utilisant la commande suivante :

   ```bash
   git clone https://github.com/NathanStad/ProjArt-Sentier_Sculpt.git
   ```

#### B – Installation des dépendances PHP

1. Assurez-vous que Composer est installé sur votre machine.
2. Installez les dépendances PHP en exécutant la commande suivante dans le répertoire du projet :

   ```bash
   composer install
   ```

#### C – Configuration de la base de données dans le fichier d'environnement

1.	Ouvrez le fichier .env dans un éditeur de texte et configurez les paramètres de la base de données :

   ```bash
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=nom_de_votre_base_de_donnees
   DB_USERNAME=nom_utilisateur_mysql
   DB_PASSWORD=mot_de_passe_mysql
   ```

#### D – Configurer & Démarrer MySQL

1.	Assurez-vous que MySQL est installé et en cours d'exécution sur votre machine.
2.	Créez un profil utilisateur pour l’application, avec nom d’utilisateur & mot de passe, en utilisant MySQL Workbench ou la ligne de commande MySQL :

   ```bash
   CREATE USER 'sculpt'@'localhost';   
   ALTER USER 'sculpt'@'localhost' IDENTIFIED WITH mysql_native_password BY 'samiLeBoGoss';  
   ```

3.	Créez une nouvelle base de données pour l'application, puis donner tous les droits à votre profil utilisateur sur cette nouvelle base de donnée. Vous pouvez utiliser MySQL Workbench ou la ligne de commande MySQL pour cela :

   ```bash
   CREATE DATABASE nom_de_votre_base_de_donnees;
   GRANT ALL PRIVILEGES ON projart.* TO 'sculpt'@'localhost';   
   ```

### Installation des dépendances & Peuplement de la BDD

#### A – Exécuter les Migrations & Seeders 

1. Exécutez les migrations pour créer les tables nécessaires dans la base de données :

   ```bash
   php artisan migrate 
   ```

2.	Exécutez les seeders pour peupler la base de données avec les données initiales :

   ```bash
   php artisan db:seed
   ```

#### B – Installer les dépendances JavaScript

1.	Assurez-vous que Node.js et npm sont installés sur votre machine.
2.	Installez les dépendances JavaScript en exécutant la commande suivante dans le répertoire du projet :

   ```bash
   npm install
   ```

#### C – Démarrer le serveur de développement

1. Démarrez le serveur de développement Laravel en exécutant la commande suivante :

   ```bash
   php artisan serve
   ```

## Wireframe & Maquette - 📎

La maquette du projet est disponible via le lien Figma ci-dessous. Divisée en plusieurs sections principales, la maquette présente la structure de l'application, incluant les cartes des sentiers, les détails des points d'intérêt, et les interfaces utilisateur pour la gestion des commentaires et des profils.

- [**Lien Figma - Ca marche**](https://www.figma.com/design/Ikt1KFCIzn1lloJM6sibUl/Wireframes?node-id=0%3A1&t=nbNG3kcRSLrpHLT9-1)

## Références - 📚

Les données utilisées dans ce projet sont principalement issues de sources locales et sont intégrées directement via les Seeders Laravel. Voici quelques ressources utilisées pour obtenir des informations supplémentaires :

- [Office du Tourisme du Canton de Vaud](https://www.region-du-leman.ch/fr/)
- [OpenStreetMap](https://www.openstreetmap.org/)
- [Wikipedia - Canton de Vaud](https://fr.wikipedia.org/wiki/Canton_de_Vaud)

