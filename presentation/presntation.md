---
marp: true
theme: default
size: 16:9
paginate: true
style: |
  @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Space+Grotesk:wght@500;600;700&display=swap');
  :root { --ink: #10243e; --blue: #0866c6; --aqua: #00a9b7; --lime: #c8e93a; --paper: #f6f9fc; --muted: #64748b; }
  section { font-family: 'DM Sans', Arial, sans-serif; color: var(--ink); background: var(--paper); padding: 54px 70px 50px; font-size: 24px; line-height: 1.35; }
  h1, h2, h3 { font-family: 'Space Grotesk', Arial, sans-serif; color: var(--ink); }
  h1 { font-size: 54px; letter-spacing: -1.8px; margin: 0 0 12px; }
  h2 { font-size: 36px; letter-spacing: -1px; margin: 0 0 25px; }
  h3 { font-size: 23px; margin: 0 0 9px; color: var(--blue); }
  h2::after { content: ''; display: block; width: 56px; height: 5px; margin-top: 11px; background: var(--lime); border-radius: 4px; }
  p, li { font-size: 23px; }
  ul { padding-left: 1.15em; }
  li { margin: 9px 0; }
  strong { color: var(--blue); }
  table { width: 100%; border-collapse: separate; border-spacing: 0; overflow: hidden; border-radius: 12px; font-size: 20px; }
  th { background: var(--blue); color: white; padding: 12px 16px; text-align: left; }
  td { padding: 13px 16px; background: white; border-bottom: 2px solid var(--paper); }
  tr:last-child td { border-bottom: 0; }
  code { font-size: 17px; }
  a { color: var(--blue); }
  section::after { color: var(--blue); font-weight: 700; }
  section.title { color: white; background: linear-gradient(125deg, #06264a 0%, #0866c6 62%, #00a9b7 100%); }
  section.title h1, section.title h2, section.title strong { color: white; }
  section.title h2::after { background: var(--lime); }
  section.title::before { content: ''; position: absolute; width: 520px; height: 520px; border: 70px solid rgba(200,233,58,.22); border-radius: 50%; right: -210px; top: -260px; }
  section.title::after { color: white; }
  section.dark { color: #eef7ff; background: #10243e; }
  section.dark h1, section.dark h2, section.dark h3, section.dark strong { color: white; }
  section.dark h2::after { background: var(--lime); }
  section.dark td { color: var(--ink); }
  section.dark::after { color: var(--lime); }
  section.image-only { display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 14px; padding: 40px 58px 48px; }
  section.image-only h1, section.image-only h2 { flex: 0 0 auto; margin: 0; text-align: center; }
  section.image-only h1 { font-size: 34px; letter-spacing: -1px; }
  section.image-only h2::after { margin: 9px auto 0; }
  section.image-only p { display: flex; align-items: center; justify-content: center; flex: 1 1 auto; min-width: 0; min-height: 0; width: 100%; max-width: 100%; margin: 0; }
  section.image-only img { display: block; box-sizing: border-box; min-width: 0; min-height: 0; width: auto; height: 100%; max-width: 100%; max-height: 100%; padding: 12px; object-fit: contain; background: white; border: 1px solid #dbe6f1; border-radius: 16px; box-shadow: 0 14px 32px rgba(16,36,62,.12); }
  .logos { position: absolute; top: 44px; right: 65px; display: flex; gap: 18px; align-items: center; }
  .logos img { height: 46px; max-width: 130px; object-fit: contain; }
---

<!-- _class: title -->

<div class="logos">
  <img src="images/ofppt-logo.png" alt="Logo OFPPT">
  <img src="images/solicode-logo.png" alt="Logo SoliCode">
</div>

# Présentation de projet
## 🌍 Smart Tourism

Plateforme web de découverte et d'organisation de voyages
**Présenté par** : Mohammed Yassir Mesbahi
**Encadré par** : ES-Sarraj Fouad

---

## Remerciements

- Équipe pédagogique et formateurs
- Encadrants et membres du jury
- Famille, collègues et camarades de formation

<!-- À personnaliser : noms des personnes à remercier -->

---

## Introduction

- Le tourisme repose de plus en plus sur le numérique
- Les voyageurs cherchent une information fiable, rapide et centralisée
- **Smart Tourism** : une plateforme pour découvrir, sauvegarder et planifier

---

## Contexte du projet

- Informations touristiques **dispersées** entre plusieurs sites
- Difficile de trouver des destinations, comparer des activités, organiser un séjour
- Première version (MVP) centrée sur :
  - l'expérience du **touriste**
  - la gestion du contenu par un **administrateur**

---

## Objectif de formation (Solicode)

- Concevoir et développer une application web complète
- Séparer **Frontend** et **Backend**
- Appliquer la POO, le pattern DAO et une architecture en couches
- Travailler avec une méthode de gestion de projet et une démarche de conception

---

## Cahier des charges

- **Cible** : voyageurs et administrateur
- **Fonctionnalités clés** : destinations, lieux, activités, favoris, avis, voyages, administration
- **Contraintes** : français, affichage mobile / tablette / ordinateur, espace admin sécurisé
- **Hors MVP** : hébergements, restaurants, guides, transport, réservation et paiement

---
<!-- _class: image-only -->
# Les Méthodes  

## Scrum

![Méthode Scrum](../rapport/SCRUM/scrum.png)

---
<!-- _class: image-only -->

## Processus 2TUP

![Processus 2TUP](../rapport/2TUP/images.jpg)

---
<!-- _class: image-only -->

## Design Thinking

![Design Thinking](../rapport/design_thinking/design_thinking.jpg)

---

## Planification

- Livraison par **sprints** (méthode Scrum)
- Priorité 1 : destinations, lieux, activités, catégories
- Priorité 2 : favoris, avis et évaluations
- Priorité 3 : voyages et étapes, espace d'administration

<!-- À compléter : dates et durée des sprints -->

---

## Branche fonctionnelle

- 🗺️ **Destinations**
- 📍 **Lieux touristiques**
- 🎯 **Activités**
- ❤️ **Favoris**
- ⭐ **Avis et évaluations**
- 🧳 **Trip Planner**
- 🤖 **Recommandations**
- 🛠️ **Administration**

---

## Carte d'empathie

- **Pense** · « Je veux un séjour réussi, sans perdre de temps »
- **Ressent** · Perdu devant trop de sources d'information
- **Dit** · « Où aller ? Que faire ? Comment organiser tout ça ? »
- **Fait** · Compare plusieurs sites, note ses idées à la main

---
<!-- _class: dark -->

## Définition du problème

- Le voyageur **perd du temps** à chercher et recouper des informations
- Il n'a pas d'endroit unique pour **garder** ses lieux et activités préférés
- L'organisation d'un séjour (dates, horaires, ordre de visite) est **manuelle**

---

## Idéation

- Une plateforme **unique** pour toute l'information touristique
- Des **favoris** et des **avis** pour choisir en confiance
- Un **planificateur de voyage** par date, heure et ordre de visite
- Des **recommandations** selon les centres d'intérêt

---

## Diagramme de cas d'utilisation

- **Touriste** · consulter destinations, lieux, activités · gérer ses favoris · publier un avis · créer et organiser un voyage
- **Administrateur** · gérer utilisateurs, destinations, catégories, lieux, activités

---

## Stack technologique

- **Frontend** · HTML5 · Tailwind CSS · JavaScript Vanilla · Fetch API
- **Backend** · PHP 8+ · POO · PDO
- **Architecture** · Controller · Service · DAO
- **Base de données** · MySQL

---

## Architecture

**Présentation** → **Logique métier** → **Accès aux données**

Navigateur · HTML / JavaScript

↓ Fetch API · HTTP / JSON

PHP · Controllers · Services · DAO

↓ PDO

MySQL · `smart_tourism`

---

## Périmètre du MVP

- Destinations · Catégories · Lieux touristiques · Activités
- Favoris · Avis et évaluations
- Voyages et étapes de voyage
- Espace d'administration

---

## Relations des données

- **Destination** → plusieurs lieux et plusieurs activités
- **Catégorie** → plusieurs lieux et plusieurs activités
- **Utilisateur** → plusieurs favoris, avis et voyages
- **Voyage** → plusieurs étapes (lieu ou activité)

---

## Base de données

- Base `smart_tourism` · **12 tables**
- `utilisateur` · `destination` · `categorie`
- `lieu_touristique` · `activite`
- `favori_lieu` · `favori_activite`
- `avis_lieu` · `avis_activite`
- `voyage` · `etape_lieu` · `etape_activite`

---

## Structure technique

- **Frontend** · HTML · CSS / Tailwind · JavaScript
- **Backend** · PHP · Controllers · Services · DAO
- **API** · JSON via Fetch API
- **Données** · MySQL

---

## Sécurité

- Requêtes préparées (PDO)
- Mots de passe hachés (`password_hash`)
- Validation des données côté Backend
- Routes d'administration protégées
- CORS limité aux origines nécessaires

---

<!-- _class: image-only -->
## Diagramme de classes

![Diagramme de classes](../conception/diagram.png)


---

## Maquettes

<!-- À remplacer par les images des maquettes -->

---

## Interfaces

- Accueil et liste des destinations
- Fiche destination · lieux · activités
- Favoris et avis
- Trip Planner
- Espace d'administration

<!-- À remplacer par les captures d'écran -->

---
<!-- _class: dark -->

## Conclusion

- Plateforme fonctionnelle pour découvrir, sauvegarder et planifier un voyage
- Base solide et évolutive, avec séparation Frontend / Backend
- Évolutions possibles · hébergements · restaurants · guides · transport · réservation · paiement en ligne · recommandations avancées
