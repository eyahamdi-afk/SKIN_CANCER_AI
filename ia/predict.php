<?php
session_start();

require_once "../config/connexion.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();

if (!isset($_GET["id_image"])) {
    die("Image introuvable.");
}

$id_image = intval($_GET["id_image"]);

// Récupérer l'image de peau
$sql = "SELECT * FROM images_peau WHERE id_image = :id_image";
$stmt = $db->prepare($sql);
$stmt->execute([
    ":id_image" => $id_image
]);

$image = $stmt->fetch();

if (!$image) {
    die("Image inexistante.");
}

$id_patient = $image["id_patient"];

// Chemin complet de l'image uploadée
$image_path = realpath("../" . $image["image_path"]);

// Chemin complet du script Python
$python_script = realpath("../python_model/predict.py");

// Chemin Python sur ton PC
$python = "C:\\Users\\ichra\\AppData\\Local\\Programs\\Python\\Python311\\python.exe";

if (!$image_path) {
    die("Chemin image invalide.");
}

if (!$python_script) {
    die("Script Python introuvable.");
}

// Commande Python
$commande = '"' . $python . '" "' . $python_script . '" "' . $image_path . '" 2>&1';

$json = shell_exec($commande);

// Extraire uniquement le JSON retourné par Python
preg_match('/\{.*\}/s', $json, $matches);

if (!isset($matches[0])) {
    die("Erreur : aucun JSON trouvé.<br><pre>$json</pre>");
}

$result = json_decode($matches[0], true);

if (!$result) {
    die("Erreur JSON invalide.<br><pre>$json</pre>");
}

if (isset($result["error"])) {
    die("Erreur Python : " . $result["error"]);
}

// Résultat retourné par le modèle .h5
$maladie = $result["maladie"];
$probabilite = $result["probabilite"];

$commentaire = "Résultat généré par le modèle IA. Ce résultat ne remplace pas un diagnostic médical.";

// Enregistrer le résultat IA
$sql = "INSERT INTO resultats_ia 
        (id_patient, id_image, maladie_predite, probabilite, commentaire)
        VALUES 
        (:id_patient, :id_image, :maladie, :probabilite, :commentaire)";

$stmt = $db->prepare($sql);

$stmt->execute([
    ":id_patient" => $id_patient,
    ":id_image" => $id_image,
    ":maladie" => $maladie,
    ":probabilite" => $probabilite,
    ":commentaire" => $commentaire
]);

// Redirection selon le rôle
if ($_SESSION["role"] === "patient") {
    header("Location: ../patient/resultat.php");
} else {
    header("Location: ../medecin/resultats_patient.php?id_patient=" . $id_patient);
}

exit();
?>