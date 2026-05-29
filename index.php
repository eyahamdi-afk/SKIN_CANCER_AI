<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>SkinCancer AI</title>
    <link rel="stylesheet" href="css/style.css?v=20">
</head>
<body>

<div class="header">
    <div class="brand">
        <img src="assets/logo.png" alt="SkinCancer AI" class="brand-logo">
       <strong>SkinCancer AI</strong>
    </div>

    <div>
        <a href="index.php">Accueil</a>
        <a href="login.php">Connexion</a>
        <a href="inscription.php">Inscription</a>
    </div>
</div>

<div class="container">

    <!-- HERO -->
    <div class="hero">
        <h1>Plateforme intelligente d’aide au dépistage du cancer de la peau</h1>
        <p>
            Analyse automatisée des images de lésions cutanées basée sur l’intelligence artificielle
            afin d’aider à distinguer les lésions bénignes et les lésions suspectes.
        </p>

        <a href="login.php" class="btn">Se connecter</a>
        <a href="inscription.php" class="btn btn-secondary">Créer un compte</a>
    </div>

    <!-- FEATURES -->
    <div class="dashboard-grid">
        <div class="stat-card">
            <h3>Analyse IA</h3>
            <p>Modèle Keras / TensorFlow</p>
        </div>

        <div class="stat-card">
            <h3>Utilisateurs</h3>
            <p>Patients / Médecins</p>
        </div>

        <div class="stat-card">
            <h3>Base de données</h3>
            <p>PostgreSQL</p>
        </div>
    </div>

    <!-- OBJECTIF -->
    <div class="card">
        <h2>Objectif du système</h2>
        <p>
            Cette plateforme permet d’assister les professionnels de santé dans l’analyse
            d’images dermatologiques et d’améliorer l’aide au dépistage précoce
            des lésions cutanées suspectes.
        </p>

        <div class="note">
            ⚠️ Le résultat généré par l’intelligence artificielle constitue une aide au dépistage.
            Il ne remplace pas un diagnostic médical réalisé par un médecin ou un dermatologue.
        </div>
    </div>

    <!-- PROCESS -->
    <div class="card">
        <h2>Fonctionnement</h2>

        <div class="dashboard-grid">

            <div class="stat-card">
                <h3>1. Upload</h3>
                <p>Image de lésion cutanée</p>
            </div>

            <div class="stat-card">
                <h3>2. Analyse</h3>
                <p>Modèle IA .h5</p>
            </div>

            <div class="stat-card">
                <h3>3. Résultat</h3>
                <p>Diagnostic assisté</p>
            </div>

        </div>
    </div>

</div>

</body>
</html>