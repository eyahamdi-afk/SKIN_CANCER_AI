<?php
session_start();
require_once "../config/connexion.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "patient") {
    header("Location: ../login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();

$sql = "SELECT id_patient FROM patients WHERE id_user = :id_user";
$stmt = $db->prepare($sql);
$stmt->execute([
    ":id_user" => $_SESSION["user_id"]
]);

$patient = $stmt->fetch();

if (!$patient) {
    die("Patient introuvable.");
}

$id_patient = $patient["id_patient"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $changement_couleur = isset($_POST["changement_couleur"]) ? 1 : 0;
    $augmentation_taille = isset($_POST["augmentation_taille"]) ? 1 : 0;
    $forme_irreguliere = isset($_POST["forme_irreguliere"]) ? 1 : 0;
    $saignement = isset($_POST["saignement"]) ? 1 : 0;
    $demangeaison = isset($_POST["demangeaison"]) ? 1 : 0;
    $douleur_locale = isset($_POST["douleur_locale"]) ? 1 : 0;

    $sql = "INSERT INTO questionnaires 
            (
                id_patient,
                changement_couleur,
                augmentation_taille,
                forme_irreguliere,
                saignement,
                demangeaison,
                douleur_locale
            )
            VALUES 
            (
                :id_patient,
                :changement_couleur,
                :augmentation_taille,
                :forme_irreguliere,
                :saignement,
                :demangeaison,
                :douleur_locale
            )";

    $stmt = $db->prepare($sql);
    $stmt->execute([
        ":id_patient" => $id_patient,
        ":changement_couleur" => $changement_couleur,
        ":augmentation_taille" => $augmentation_taille,
        ":forme_irreguliere" => $forme_irreguliere,
        ":saignement" => $saignement,
        ":demangeaison" => $demangeaison,
        ":douleur_locale" => $douleur_locale
    ]);

    header("Location: upload_image.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Questionnaire - SkinCancer AI</title>
    <link rel="stylesheet" href="../css/style.css?v=20">
</head>
<body>

<div class="header">
    <div class="brand">
        <img src="../assets/logo.png" alt="SkinCancer AI" class="brand-logo">
       <strong>SkinCancer AI - Questionnaire</strong>
    </div>

    <div>
        <a href="dashboard.php">Accueil patient</a>
        <a href="../logout.php">Déconnexion</a>
    </div>
</div>

<div class="container">

    <div class="hero">
        <h1>Questionnaire médical</h1>
        <p>
            Répondez aux questions suivantes afin de compléter l’analyse de votre image cutanée.
        </p>
    </div>

    <div class="card">
        <h2>Symptômes ou signes observés</h2>

        <form method="POST">

            <label class="check-line">
                <input type="checkbox" name="changement_couleur">
                Changement de couleur de la lésion
            </label>

            <label class="check-line">
                <input type="checkbox" name="augmentation_taille">
                Augmentation récente de la taille
            </label>

            <label class="check-line">
                <input type="checkbox" name="forme_irreguliere">
                Forme irrégulière ou asymétrique
            </label>

            <label class="check-line">
                <input type="checkbox" name="saignement">
                Saignement ou croûte persistante
            </label>

            <label class="check-line">
                <input type="checkbox" name="demangeaison">
                Démangeaison
            </label>

            <label class="check-line">
                <input type="checkbox" name="douleur_locale">
                Douleur locale au niveau de la lésion
            </label>

            <button type="submit">Continuer</button>
            <a href="dashboard.php" class="btn btn-secondary">Retour à l’accueil</a>
        </form>

        <div class="note">
            Ces informations complètent l’analyse IA. Elles ne remplacent pas un avis médical
            et doivent être interprétées par un médecin ou un dermatologue.
        </div>
    </div>

</div>

</body>
</html>