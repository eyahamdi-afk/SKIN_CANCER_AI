# SKIN_CANCER_AI

Application web intelligente pour la détection du cancer de la peau à partir d’images, avec une interface Patient et Médecin.

---

# Présentation du projet

Ce projet a été développé dans le but de créer une plateforme médicale intelligente permettant la détection du cancer de la peau à partir d’images dermatologiques.

L’application permet :

* l’inscription et la connexion des utilisateurs ;
* l’ajout d’images de peau ;
* l’analyse automatique par intelligence artificielle ;
* l’affichage des résultats de prédiction ;
* le suivi des patients par les médecins.

---

# Contenu du dépôt

| Dossier / Fichier | Description                                     |
| ----------------- | ----------------------------------------------- |
| `assets/`         | Images, logos et ressources visuelles           |
| `config/`         | Configuration et connexion à la base de données |
| `css/`            | Fichiers de style CSS                           |
| `ia/`             | Partie intelligence artificielle                |
| `medecin/`        | Interface Médecin                               |
| `patient/`        | Interface Patient                               |
| `python_model/`   | Scripts Python et modèle IA                     |
| `uploads/peau/`   | Images uploadées par les utilisateurs           |
| `screenshots/`    | Captures d’écran du projet                      |
| `demo/`           | Vidéo de démonstration                          |
| `data.sql`        | Base de données PostgreSQL                      |
| `README.md`       | Documentation complète du projet                |

---

# Fonctionnalités principales

## Interface Patient

* Création de compte
* Connexion sécurisée
* Upload des images de peau
* Questionnaire médical
* Consultation des résultats IA

## Interface Médecin

* Gestion des patients
* Consultation des dossiers médicaux
* Accès aux résultats des analyses IA
* Suivi des patients

## Intelligence Artificielle

* Analyse des images dermatologiques
* Détection du cancer de la peau
* Classification des résultats
* Intégration Python + PHP

---

# Technologies utilisées

* PHP
* HTML
* CSS
* JavaScript
* Python
* PostgreSQL
* GitHub
* VS Code
* WAMP Server

---

# Base de données

La base de données utilisée dans le projet est disponible ici :

* [Télécharger la base de données](data.sql)

## Importation de la base de données

1. Ouvrir PostgreSQL / pgAdmin
2. Créer une nouvelle base de données
3. Importer le fichier `data.sql`
4. Configurer les informations de connexion dans :

```text
config/connexion.php
```

---

# Modèle d’Intelligence Artificielle

Le modèle IA utilisé pour la prédiction se trouve dans :

* [Voir le dossier du modèle IA](python_model/)

Ce dossier contient :

* les scripts Python ;
* le modèle entraîné ;
* les fichiers nécessaires à l’analyse des images.

Le système IA est intégré avec l’application PHP afin d’automatiser les résultats de prédiction.

---

# Démonstration vidéo

Le dépôt contient une vidéo de démonstration montrant :

* la connexion ;

* l’inscription ;

* l’ajout d’images ;

* l’analyse IA ;

* l’affichage des résultats ;

* l’interface Patient ;

* l’interface Médecin.

* [Voir la vidéo de démonstration](demo/video_acceleree_1_5x_182501.mp4)

---

# Captures d’écran

Les captures d’écran du projet sont disponibles ici :

* [Voir le dossier des captures](screenshots/)

Quelques interfaces principales :

* [Page d’accueil](screenshots/accueil.png)
* [Page de connexion](screenshots/connexion.png)
* [Page d’inscription](screenshots/inscription.png)
* [Interface médecin](screenshots/interface%20medecin.png)
* [Questionnaire patient](screenshots/questionnaire.png)
* [Résultat patient](screenshots/resultat%20patient.png)

---

# Installation du projet

## 1. Cloner le dépôt GitHub

```bash
git clone https://github.com/eyahamdi-afk/SKIN_CANCER_AI.git
```

## 2. Placer le projet dans WAMP

Déplacer le dossier dans :

```text
wamp64/www/
```

## 3. Lancer WAMP Server

Activer :

* Apache
* PostgreSQL

## 4. Importer la base de données

Importer le fichier :

```text
data.sql
```

## 5. Ouvrir le projet dans le navigateur

```text
http://localhost/SKIN_CANCER_AI/
```

---

# Auteur

Projet réalisé par **Eya Hamdi**

Étudiante à l’ENSTAB spécialisée en technologie avancée.
