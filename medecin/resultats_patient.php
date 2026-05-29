<?php
session_start();
require_once "../config/connexion.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "medecin") {
    header("Location: ../login.php");
    exit();
}

if (!isset($_GET["id_patient"])) {
    die("Patient introuvable.");
}

$id_patient = intval($_GET["id_patient"]);

$database = new Database();
$db = $database->getConnection();

/* RÉCUPÉRER INFORMATIONS PATIENT */
$sqlPatient = "SELECT p.*, u.nom, u.prenom, u.email
               FROM patients p
               JOIN users u ON p.id_user = u.id_user
               WHERE p.id_patient = :id_patient";

$stmtPatient = $db->prepare($sqlPatient);
$stmtPatient->execute([
    ":id_patient" => $id_patient
]);

$patient = $stmtPatient->fetch();

if (!$patient) {
    die("Patient inexistant.");
}

/* RÉCUPÉRER RÉSULTATS IA */
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
    <title>Résultats patient - SkinCancer AI</title>
    <link rel="stylesheet" href="../css/style.css?v=20">
</head>
<body>

<div class="header">
     <div class="brand">
        <img src="../assets/logo.png" alt="SkinCancer AI" class="brand-logo">
       <strong>SkinCancer AI - Résultats patient</strong>
    </div>     
    
    <div>
        <a href="dossiers.php">Retour dossiers</a>
        <a href="dashboard.php">Accueil médecin</a>
        <a href="../logout.php">Déconnexion</a>
    </div>
</div>

<div class="container">

    <div class="hero">
        <h1>Dossier patient</h1>
        <p>Consultation des informations du patient et des résultats d’analyse IA.</p>
    </div>

    <div class="card">
        <h2>Informations patient</h2>

        <p>
            <strong>Nom :</strong>
            <?php echo htmlspecialchars($patient["nom"] . " " . $patient["prenom"]); ?>
        </p>

        <p>
            <strong>Email :</strong>
            <?php echo htmlspecialchars($patient["email"]); ?>
        </p>

        <p>
            <strong>Âge :</strong>
            <?php echo htmlspecialchars($patient["age"]); ?>
        </p>

        <p>
            <strong>Sexe :</strong>
            <?php echo htmlspecialchars($patient["sexe"]); ?>
        </p>

        <p>
            <strong>Téléphone :</strong>
            <?php echo htmlspecialchars($patient["telephone"] ?? "Non renseigné"); ?>
        </p>

        <p>
            <strong>Adresse :</strong>
            <?php echo htmlspecialchars($patient["adresse"] ?? "Non renseignée"); ?>
        </p>

        <a class="btn" href="ajouter_image.php?id_patient=<?php echo $id_patient; ?>">
            Ajouter une nouvelle image
        </a>
    </div>

    <div class="card">
        <h2>Résultats IA</h2>

        <?php if (count($resultats) === 0): ?>
            <p>Aucun résultat disponible pour ce patient.</p>
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

            <div class="card result-card">

                <img 
                    src="../<?php echo htmlspecialchars($r["image_path"]); ?>" 
                    class="preview"
                    alt="Image cutanée analysée"
                >

                <h3 class="result-title">
                    <?php echo htmlspecialchars(strtoupper($maladie)); ?>
                </h3>

                <span class="badge <?php echo $badgeClass; ?>">
                    <?php echo htmlspecialchars($badgeText); ?>
                </span>

                <p>
                    <strong>Probabilité :</strong>
                    <span class="probability">
                        <?php echo htmlspecialchars($r["probabilite"]); ?>%
                    </span>
                </p>

                <?php if (!empty($r["commentaire"])): ?>
                    <p>
                        <strong>Commentaire :</strong>
                        <?php echo htmlspecialchars($r["commentaire"]); ?>
                    </p>
                <?php endif; ?>

                <p>
                    <strong>Date :</strong>
                    <?php echo htmlspecialchars($r["date_resultat"]); ?>
                </p>

                <div class="note">
                    Résultat généré par le modèle d’intelligence artificielle.
                    Une validation clinique par un médecin ou un dermatologue est recommandée.
                </div>

            </div>

        <?php endforeach; ?>

    </div>

</div>

</body>
</html>