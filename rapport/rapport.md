---
layout: default
title: Rapport du projet fil rouge
---

# Rapport du projet fil rouge

> **Note sur les sources :** les documents disponibles mentionnent plusieurs contextes différents : une plateforme de gestion de copropriété, un prototype de livraison de repas, un exemple de gestion de genres et des noms de projets qui ne concordent pas. Les éléments ci-dessous sont donc présentés dans leur contexte documenté, sans les attribuer automatiquement au projet principal.

## Table des matières

- [Rapport du projet fil rouge](#rapport-du-projet-fil-rouge)
  - [Table des matières](#table-des-matières)
  - [Remerciements](#remerciements)
  - [Introduction](#introduction)
  - [Contexte du projet](#contexte-du-projet)
  - [Objectif de formation (Solicode)](#objectif-de-formation-solicode)
  - [Cahier des charges](#cahier-des-charges)
  - [1. Contexte \& Enjeux](#1-contexte--enjeux)
  - [2. Besoins Fonctionnels](#2-besoins-fonctionnels)
    - [Hors périmètre de la première version](#hors-périmètre-de-la-première-version)
  - [3. Contraintes](#3-contraintes)
  - [Points d'attention](#points-dattention)
  - [Méthodologie de travail](#méthodologie-de-travail)
    - [Méthodologie Scrum](#méthodologie-scrum)
      - [Sprint 1](#sprint-1)
      - [Sprint 2](#sprint-2)
    - [Processus 2TUP](#processus-2tup)
    - [Design Thinking](#design-thinking)
  - [Planification](#planification)
  - [Branche fonctionnelle](#branche-fonctionnelle)
    - [Carte d'empathie](#carte-dempathie)
    - [Définition du problème](#définition-du-problème)
    - [Idéation](#idéation)
    - [Diagramme de cas d'utilisation](#diagramme-de-cas-dutilisation)
  - [Branche technique](#branche-technique)
    - [Stack technologique](#stack-technologique)
    - [Architecture logicielle](#architecture-logicielle)
    - [Prototype : fonctionnalités et classes](#prototype--fonctionnalités-et-classes)
  - [Conception](#conception)
    - [Diagramme de classes](#diagramme-de-classes)
    - [Maquettes](#maquettes)
  - [Réalisation](#réalisation)
    - [Interfaces](#interfaces)
  - [Conclusion](#conclusion)

## Remerciements

Je tiens à exprimer ma profonde et sincère gratitude envers toutes les personnes qui ont contribué, de près ou de loin, à l'aboutissement de ce projet de fin d'études. Ce travail n'aurait pu voir le jour sans leur précieux concours, leur engagement et leur bienveillance.

En premier lieu, j'adresse mes remerciements les plus chaleureux à M. ES-SARRAJ Fouad., mon encadrante pédagogique, dont les conseils avisés, la rigueur scientifique et la disponibilité permanente ont été des guides indispensables tout au long de la conduite de ce projet. Ses orientations méthodologiques et ses retours critiques ont considérablement enrichi la qualité de ce travail.

Je remercie également l'ensemble du corps professoral et administratif de Solicode pour la qualité de la formation dispensée, qui m'a fourni les outils conceptuels et techniques nécessaires à la réalisation de ce projet. Chaque cours, chaque atelier pratique et chaque projet académique ont contribué à forger les compétences mobilisées dans ce mémoire.

Ma reconnaissance va également à mes camarades de promotion pour les échanges intellectuels fructueux, l'entraide permanente et le partage de connaissances qui ont caractérisé notre cohabitation académique. Ces interactions ont nourri ma réflexion et stimulé ma créativité tout au long du processus de développement.

Enfin, je ne saurais clore ces remerciements sans exprimer ma profonde gratitude envers ma famille, dont le soutien moral et matériel m'a permis de mener mes études dans les meilleures conditions possibles. Leur foi en mes capacités a été le moteur de ma persévérance.

Merci à tous.


## Introduction

## Contexte du projet

## Objectif de formation (Solicode)

## Cahier des charges


## 1. Contexte & Enjeux
- **Pourquoi ce projet ?** : Les informations touristiques sont dispersées entre plusieurs sites. Il est difficile pour un voyageur de trouver des destinations, de découvrir les lieux à visiter, de comparer les activités et d'organiser son séjour. Smart Tourism regroupe tout cela dans une seule plateforme web.
- **Cible** :
    - **Voyageur / touriste** : veut découvrir des destinations, choisir des lieux et activités, et préparer son voyage.
    - **Administrateur** : gère le contenu de la plateforme (destinations, lieux, activités, utilisateurs).
- **Tons & Style** : Moderne, convivial et inspirant (à confirmer avec le client). Navigation simple, visuels attractifs, lisible sur mobile, tablette et ordinateur.

## 2. Besoins Fonctionnels
- **Besoin Métier** : Permettre à un voyageur de découvrir des destinations, de consulter les informations utiles, de garder ses coups de cœur, de donner son avis et d'organiser ses voyages. Permettre à l'administrateur de tenir le contenu à jour facilement.
- **Fonctionnalités Clés** :
    - Consulter les destinations touristiques (villes, régions, localisation)
    - Consulter les lieux touristiques d'une destination (description, adresse, horaires, prix d'entrée, images) et les filtrer par catégorie
    - Consulter les activités d'une destination (description, durée, prix, niveau) et les filtrer par catégorie
    - Ajouter des lieux et des activités à ses favoris, les consulter et les supprimer
    - Publier un avis et une note sur un lieu ou une activité, puis les modifier ou les supprimer
    - Créer et gérer ses voyages : choisir une destination, ajouter des lieux et des activités, organiser les étapes par date, heure et ordre de visite
    - Recevoir des recommandations selon ses centres d'intérêt (critères à définir pendant le projet)
    - Gérer le contenu de la plateforme (espace réservé à l'administrateur) : utilisateurs, destinations, catégories, lieux, activités
- **Contenus Indispensables** :
    - Fiches des destinations, avec présentation et localisation
    - Fiches des lieux touristiques et des activités, avec descriptions et images
    - Liste des catégories (pour classer les lieux et activités)
    - Espace personnel du voyageur (favoris, avis, voyages)
    - Espace d'administration protégé par identifiant et mot de passe

### Hors périmètre de la première version
À prévoir plus tard, non inclus dans cette version :
- Hébergements (hôtels, riads, maisons d'hôtes)
- Restaurants et menus
- Guides touristiques
- Services de transport
- Réservation et paiement en ligne
- Espaces dédiés aux professionnels du tourisme

## 3. Contraintes
- **Deadline** : [À définir : date ou durée]
- **Technique** : Plateforme web avec gestion de comptes et de données (**à valider**, voir point d'attention ci-dessous)
- **Langue** : Français
- **Sécurité** : Mots de passe protégés, espace administrateur sécurisé, données des utilisateurs protégées
- **Compatibilité** : Affichage adapté au mobile, à la tablette et à l'ordinateur

## Points d'attention
- Le modèle initial impose un **site statique**. Or Smart Tourism a besoin de comptes utilisateurs, de favoris, d'avis, de voyages et d'un espace d'administration : ces fonctions nécessitent un site dynamique avec base de données. À faire valider par le client.
- La **deadline** et le **ton exact** du site restent à confirmer.


## Méthodologie de travail

### Méthodologie Scrum

Scrum est une méthode agile qui organise le travail en cycles courts et favorise la livraison progressive de fonctionnalités. Elle s'appuie sur trois principes :

- **Transparence** : rendre visibles les objectifs, les tâches et leur avancement.
- **Inspection** : examiner régulièrement le travail réalisé et les éventuels blocages.
- **Adaptation** : ajuster les priorités et le plan de travail à partir des résultats et des retours.

Le document disponible cite des sprints, mais ne précise ni leurs objectifs ni les tâches réalisées. Le nom de projet qu'il mentionne ne concorde pas avec les autres documents ; son application au projet principal reste donc à confirmer.

![Schéma de la méthode Scrum](SCRUM/scrum.png)

*Figure 1 — Schéma de la méthode Scrum.*

#### Sprint 1

#### Sprint 2

### Processus 2TUP

2TUP (*Two Tracks Unified Process*) est une démarche itérative organisée autour de deux axes menés en parallèle :

- **Branche fonctionnelle** : compréhension des utilisateurs, des processus et des besoins métier.
- **Branche technique** : étude des contraintes, de l'architecture et des choix technologiques.

Les deux branches convergent vers la réalisation, qui peut comprendre la conception, le développement, les tests et le déploiement. Le document disponible décrit cette démarche de manière générale, sans préciser son application au projet.

![Schéma du processus 2TUP](2TUP/images.jpg)

*Figure 2 — Schéma général du processus 2TUP.*

### Design Thinking

Le Design Thinking est une démarche de résolution de problèmes centrée sur les besoins des utilisateurs. Elle encourage la collaboration et l'exploration de solutions avant leur mise en œuvre.

Ses principales étapes sont :

1. **Empathie** : observer et interroger les utilisateurs pour comprendre leurs besoins et leurs difficultés.
2. **Définition** : synthétiser les informations recueillies et formuler le problème à résoudre.
3. **Idéation** : proposer plusieurs solutions possibles, sans écarter prématurément les idées.
4. **Prototype** : matérialiser une solution sous une forme simplifiée.
5. **Test** : recueillir les retours des utilisateurs et améliorer la solution.

![Étapes du Design Thinking](design_thinking/design_thinking.jpg)

*Figure 3 — Étapes du Design Thinking.*

## Planification

## Branche fonctionnelle

### Carte d'empathie

### Définition du problème

### Idéation

### Diagramme de cas d'utilisation

## Branche technique

### Stack technologique

Le document consacré à la branche technique présente les choix suivants pour une plateforme de gestion de copropriété immobilière :

- **Backend** : PHP 8.2 ou supérieur et Laravel 12.
- **Gestion des rôles et permissions** : Spatie Laravel Permission 7.4.
- **Application mobile** : NativePHP est cité pour une éventuelle déclinaison mobile.
- **Frontend** : Blade, Tailwind CSS, Alpine et Preline.
- **Compilation des ressources** : Vite.
- **Base de données** : MySQL.
- **Diagrammes** : Mermaid et PlantUML.

Ces choix sont ceux consignés dans le document technique. Aucun fichier de configuration ou code source de l'application principale n'est fourni ici pour les vérifier.

### Architecture logicielle

Le document d'architecture décrit un **exemple distinct de gestion de genres** : une interface HTML/JavaScript communique avec une API PHP et les données sont lues ou enregistrées dans un fichier JSON. Il présente également les notions MVC et trois-tiers.

Cet exemple n'est pas cohérent avec la stack Laravel/MySQL décrite pour la plateforme de gestion de copropriété. En l'absence d'éléments permettant de relier les deux, cette architecture ne peut pas être présentée comme celle du projet principal.

### Prototype : fonctionnalités et classes

Le projet technique préparatoire décrit un prototype CRUD de **livraison de repas**. Il permet à un propriétaire de gérer plusieurs restaurants, les plats de leurs menus et leurs catégories.

Fonctionnalités décrites :

- gérer plusieurs restaurants ;
- ajouter, consulter, modifier et supprimer les plats d'un restaurant ;
- gérer les catégories alimentaires ;
- associer plusieurs catégories à un plat et organiser les plats par catégorie.

Classes et relations documentées :

- `RestaurantOwner` possède plusieurs `Restaurant` ;
- `Restaurant` contient plusieurs `MenuItem` et plusieurs `FoodCategory` ;
- `MenuItem` et `FoodCategory` sont en relation plusieurs-à-plusieurs.

Ce prototype est présenté comme un travail réalisé avant le développement du projet principal ; son lien fonctionnel avec la plateforme de gestion de copropriété n'est pas précisé.

## Conception

### Diagramme de classes

### Maquettes

## Réalisation

### Interfaces

## Conclusion
