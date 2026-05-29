<?php
session_start();
require_once "../config/connexion.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "medecin") {
    header("Location: ../login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();

$totalPatients = $db->query("SELECT COUNT(*) FROM patients")->fetchColumn();
$totalImages = $db->query("SELECT COUNT(*) FROM images_peau")->fetchColumn();
$totalResultats = $db->query("SELECT COUNT(*) FROM resultats_ia")->fetchColumn();

$sqlRecent = "SELECT u.nom, u.prenom, r.maladie_predite, r.probabilite, r.date_resultat
              FROM resultats_ia r
              JOIN patients p ON r.id_patient = p.id_patient
              JOIN users u ON p.id_user = u.id_user
              ORDER BY r.date_resultat DESC
              LIMIT 5";

$stmtRecent = $db->prepare($sqlRecent);
$stmtRecent->execute();
$recentResults = $stmtRecent->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Espace Médecin - SkinCancer AI</title>
    <link rel="stylesheet" href="../css/style.css?v=20">
</head>
<body>

<div class="header">
    <div class="brand">
        <img src="../assets/logo.png" alt="SkinCancer AI" class="brand-logo">
        <strong>SkinCancer AI - Espace Médecin</strong>    
    </div>  
    
    <div>
        <a href="ajouter_patient.php">Ajouter patient</a>
        <a href="dossiers.php">Dossiers</a>
        <a href="../logout.php">Déconnexion</a>
    </div>
</div>

<div class="container">

    <div class="hero">
        <h1>Bienvenue Dr. <?php echo htmlspecialchars($_SESSION["nom"]); ?></h1>
        <p>
            Tableau de bord clinique pour le suivi des patients, l’analyse des images cutanées
            et la consultation des résultats générés par le modèle IA.
        </p>
    </div>

    <div class="dashboard-grid" style="margin-top:30px;">
        <div class="stat-card">
            <h3>👤 Patients suivis</h3>
            <p><?php echo htmlspecialchars($totalPatients); ?></p>
        </div>

        <div class="stat-card">
            <h3>🖼️ Images analysées</h3>
            <p><?php echo htmlspecialchars($totalImages); ?></p>
        </div>

        <div class="stat-card">
            <h3>🧠 Résultats IA</h3>
            <p><?php echo htmlspecialchars($totalResultats); ?></p>
        </div>
    </div>

    <div class="card">
        <h2>Actions rapides</h2>
        <a href="ajouter_patient.php" class="btn">Créer un dossier patient</a>
        <a href="dossiers.php" class="btn btn-secondary">Consulter les dossiers</a>
    </div>

    <div class="card">
        <h2>Dernières analyses IA</h2>

        <?php if (count($recentResults) === 0): ?>
            <p>Aucune analyse enregistrée pour le moment.</p>
        <?php else: ?>
            <table class="table">
                <tr>
                    <th>Patient</th>
                    <th>Résultat IA</th>
                    <th>Interprétation</th>
                    <th>Probabilité</th>
                    <th>Date</th>
                </tr>

                <?php foreach ($recentResults as $r): ?>
                    <?php
                        $maladie = strtolower($r["maladie_predite"]);

                        if ($maladie === "benign" || $maladie === "bénigne") {
                            $badgeClass = "badge-success";
                            $interpretation = "Lésion probablement bénigne";
                        } else {
                            $badgeClass = "badge-danger";
                            $interpretation = "Lésion suspecte";
                        }
                    ?>

                    <tr>
                        <td>
                            <?php echo htmlspecialchars($r["nom"] . " " . $r["prenom"]); ?>
                        </td>

                        <td>
                            <span class="badge <?php echo $badgeClass; ?>">
                                <?php echo htmlspecialchars(strtoupper($r["maladie_predite"])); ?>
                            </span>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($interpretation); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($r["probabilite"]); ?>%
                        </td>

                        <td>
                            <?php echo htmlspecialchars($r["date_resultat"]); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>

</div>

</body>
</html>