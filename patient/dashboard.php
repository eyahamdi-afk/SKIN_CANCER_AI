<?php
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "patient") {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Espace Patient - SkinCancer AI</title>
    <link rel="stylesheet" href="../css/style.css?v=20">
</head>
<body>

<div class="header">

     <div class="brand">
        <img src="../assets/logo.png" alt="SkinCancer AI" class="brand-logo">
       <strong>SkinCancer AI - Espace Patient</strong>
    </div>   
    
    <div>
        <a href="questionnaire.php">Nouveau dépistage</a>
        <a href="resultat.php">Mes résultats</a>
        <a href="../logout.php">Déconnexion</a>
    </div>
</div>

<div class="container">

    <div class="hero">
        <h1>Bienvenue <?php echo htmlspecialchars($_SESSION["nom"]); ?></h1>
        <p>
            Envoyez une image de lésion cutanée et répondez à un questionnaire afin
            d’obtenir une aide au dépistage générée par intelligence artificielle.
        </p>
    </div>

    <div class="dashboard-grid" style="grid-template-columns: repeat(2, 1fr);">
        <div class="stat-card">
            <h3>🩺 Nouveau dépistage</h3>
            <p>Image cutanée</p>
            <a href="questionnaire.php" class="btn">Commencer</a>
        </div>

        <div class="stat-card">
            <h3>📊 Mes résultats</h3>
            <p>Analyses IA</p>
            <a href="resultat.php" class="btn btn-secondary">Consulter</a>
        </div>
    </div>

    <div class="card">
        <h2>Information importante</h2>
        <div class="note">
            Le résultat généré par l’IA est indicatif. Il ne remplace pas une consultation médicale
            et doit être confirmé par un médecin ou un dermatologue.
        </div>
    </div>

</div>

</body>
</html>