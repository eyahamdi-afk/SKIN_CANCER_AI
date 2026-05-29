<?php
session_start();
require_once "../config/connexion.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "medecin") {
    header("Location: ../login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();

$sql = "SELECT p.*, u.nom, u.prenom, u.email
        FROM patients p
        JOIN users u ON p.id_user = u.id_user
        ORDER BY p.id_patient DESC";

$stmt = $db->prepare($sql);
$stmt->execute();

$patients = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Dossiers patients - SkinCancer AI</title>
    <link rel="stylesheet" href="../css/style.css?v=20">
</head>
<body>

<div class="header">
     <div class="brand">
        <img src="../assets/logo.png" alt="SkinCancer AI" class="brand-logo">
        <strong>SkinCancer AI - Dossiers patients</strong>    
    </div>    
    
    <div>
        <a href="dashboard.php">Accueil médecin</a>
        <a href="ajouter_patient.php">Ajouter patient</a>
        <a href="../logout.php">Déconnexion</a>
    </div>
</div>

<div class="container">

    <div class="hero">
        <h1>Dossiers patients</h1>
        <p>
            Consultez la liste des patients, ajoutez une image cutanée
            et accédez aux résultats générés par l’intelligence artificielle.
        </p>
    </div>

    <div class="card">
        <h2>Liste des patients</h2>

        <?php if (count($patients) === 0): ?>
            <p>Aucun patient trouvé.</p>
        <?php endif; ?>

        <?php foreach ($patients as $p): ?>
            <div class="card">
                <h3>
                    <?php echo htmlspecialchars($p["nom"] . " " . $p["prenom"]); ?>
                </h3>

                <p>
                    <strong>Email :</strong> 
                    <?php echo htmlspecialchars($p["email"]); ?>
                </p>

                <p>
                    <strong>Âge :</strong> 
                    <?php echo htmlspecialchars($p["age"]); ?>
                </p>

                <p>
                    <strong>Sexe :</strong> 
                    <?php echo htmlspecialchars($p["sexe"]); ?>
                </p>

                <p>
                    <strong>Téléphone :</strong> 
                    <?php echo htmlspecialchars($p["telephone"] ?? "Non renseigné"); ?>
                </p>

                <p>
                    <strong>Adresse :</strong> 
                    <?php echo htmlspecialchars($p["adresse"] ?? "Non renseignée"); ?>
                </p>

                <a class="btn" href="ajouter_image.php?id_patient=<?php echo $p["id_patient"]; ?>">
                    Ajouter image / analyser
                </a>

                <a class="btn btn-secondary" href="resultats_patient.php?id_patient=<?php echo $p["id_patient"]; ?>">
                    Voir résultats
                </a>
            </div>
        <?php endforeach; ?>
    </div>

</div>

</body>
</html>