<?php
session_start();
require_once "../config/connexion.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "patient") {
    header("Location: ../login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();

/* RÉCUPÉRER ID PATIENT */
$stmt = $db->prepare("SELECT id_patient FROM patients WHERE id_user = :id_user");
$stmt->execute([
    ":id_user" => $_SESSION["user_id"]
]);

$patient = $stmt->fetch();

if (!$patient) {
    die("Erreur : patient introuvable.");
}

$id_patient = $patient["id_patient"];

/* RÉCUPÉRER LES RÉSULTATS */
$sql = "SELECT r.*, i.image_path
        FROM resultats_ia r
        JOIN images_peau i ON r.id_image = i.id_image
        WHERE r.id_patient = :id_patient
        ORDER BY r.date_resultat DESC";

$stmt = $db->prepare($sql);
$stmt->execute([
    ":id_patient" => $id_patient
]);

$resultats = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Mes résultats - SkinCancer AI</title>
    <link rel="stylesheet" href="../css/style.css?v=20">
</head>
<body>

<div class="header">
    <div class="brand">
        <img src="../assets/logo.png" alt="SkinCancer AI" class="brand-logo">
       <strong>SkinCancer AI - Mes résultats</strong>
    </div>    
    
    <div>
        <a href="dashboard.php">Accueil</a>
        <a href="../logout.php">Déconnexion</a>
    </div>
</div>

<div class="container">

    <!-- HERO -->
    <div class="hero">
        <h1>Historique de mes analyses</h1>
        <p>Consultez les résultats générés par l’intelligence artificielle à partir de vos images cutanées.</p>
    </div>

    <div class="card">
        <a href="dashboard.php" class="btn btn-secondary">← Retour</a>
        <a href="questionnaire.php" class="btn">Nouvelle analyse</a>
    </div>

    <?php if (count($resultats) === 0): ?>
        <div class="card">
            <p>Aucun résultat disponible pour le moment.</p>
        </div>
    <?php endif; ?>

    <?php foreach ($resultats as $r): ?>

        <?php
            $maladie = strtolower($r["maladie_predite"]);

            if ($maladie === "benign" || $maladie === "bénigne") {
                $badgeClass = "badge-success";
                $badgeText = "Lésion probablement bénigne";
            } else {
                $badgeClass = "badge-danger";
                $badgeText = "Lésion suspecte détectée";
            }
        ?>

        <div class="card" style="display:flex; gap:20px; align-items:center;">

            <!-- IMAGE -->
            <div>
                <img class="preview" 
                     src="../<?php echo htmlspecialchars($r["image_path"]); ?>" 
                     alt="Image cutanée analysée">
            </div>

            <!-- INFO -->
            <div style="flex:1;">

                <h2 class="result-title">
                    <?php echo htmlspecialchars(strtoupper($maladie)); ?>
                </h2>

                <span class="badge <?php echo $badgeClass; ?>">
                    <?php echo $badgeText; ?>
                </span>

                <p class="probability">
                    Probabilité : <?php echo htmlspecialchars($r["probabilite"]); ?>%
                </p>

                <?php if (!empty($r["commentaire"])): ?>
                    <p><?php echo htmlspecialchars($r["commentaire"]); ?></p>
                <?php endif; ?>

                <p>
                    <strong>Date :</strong> 
                    <?php echo htmlspecialchars($r["date_resultat"]); ?>
                </p>

                <div class="note">
                    Résultat généré automatiquement par IA.  
                    Il ne remplace pas un diagnostic médical réalisé par un médecin ou un dermatologue.
                </div>

            </div>

        </div>

    <?php endforeach; ?>

</div>

</body>
</html>