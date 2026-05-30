# SKIN_CANCER_AI

Application web intelligente pour la détection du cancer de la peau à partir d’images, avec une interface patient et médecin.

---

## Objectif du projet

Ce projet permet :

* l’inscription et la connexion des patients et médecins ;
* l’ajout d’images de peau ;
* l’analyse par un modèle d’intelligence artificielle ;
* l’affichage du résultat de prédiction ;
* le suivi des patients par le médecin.

---

## Fonctionnalités principales

### Interface Patient

* Création de compte
* Connexion sécurisée
* Upload d’images de peau
* Questionnaire médical
* Consultation des résultats IA

### Interface Médecin

* Gestion des patients
* Consultation des dossiers médicaux
* Accès aux résultats des prédictions IA
* Suivi des analyses

### Intelligence Artificielle

* Analyse d’images dermatologiques
* Détection du cancer de la peau
* Classification des résultats
* Intégration Python + PHP

---

## Technologies utilisées

* PHP
* HTML / CSS
* JavaScript
* Python
* PostgreSQL
* Intelligence Artificielle
* GitHub
* VS Code
* WAMP Server

---

## Structure du projet

```text
SKIN_CANCER_AI/
│
├── assets/               # Images et ressources
├── config/               # Connexion à la base de données
├── css/                  # Fichiers de style
├── ia/                   # Partie intelligence artificielle
├── medecin/              # Interface médecin
├── patient/              # Interface patient
├── python_model/         # Scripts et modèles Python
├── uploads/              # Images uploadées
├── data.sql              # Base de données PostgreSQL
├── index.php             # Page d’accueil
├── login.php             # Connexion
├── inscription.php       # Inscription
└── README.md
```

---

## Base de données

Le fichier `data.sql` contient la structure complète de la base de données utilisée dans le projet.

### Importation de la base de données

1. Ouvrir PostgreSQL / pgAdmin
2. Créer une nouvelle base de données
3. Importer le fichier `data.sql`
4. Configurer les paramètres de connexion dans le dossier `config/`

---

## Installation du projet

### 1. Cloner le dépôt GitHub

```bash
git clone https://github.com/eyahamdi-afk/SKIN_CANCER_AI.git
```

### 2. Placer le projet dans WAMP

Déplacer le dossier dans :

```text
wamp64/www/
```

### 3. Lancer WAMP Server

Activer :

* Apache
* PostgreSQL

### 4. Importer la base de données

Importer le fichier :

```text
data.sql
```

### 5. Ouvrir le projet

Dans le navigateur :

```text
http://localhost/SKIN_CANCER_AI/
```

---

## Démonstration vidéo

Le dépôt contient une vidéo de démonstration montrant :

* la connexion ;
* l’upload des images ;
* l’analyse IA ;
* l’affichage des résultats ;
* les interfaces patient et médecin.

---

## Captures d’écran

Le projet contient plusieurs captures d’écran de :

* la page d’accueil ;
* l’interface patient ;
* l’interface médecin ;
* les formulaires ;
* les résultats IA.

---

## Intelligence Artificielle

Le système IA utilise Python pour :

* le traitement des images ;
* la prédiction ;
* l’analyse des données médicales.

Le modèle est intégré avec l’application PHP afin d’automatiser les résultats.

---

## Auteur

Projet réalisé par **Eya Hamdi**.

Etudiante à l'ENSTAB spécialisée en technologie avancée 
