# Cahier des Charges : Smart Tourism

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
