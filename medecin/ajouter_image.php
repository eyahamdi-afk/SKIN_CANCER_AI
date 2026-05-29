<?php
session_start();
require_once "../config/connexion.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "medecin") {
    header("Location: ../login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();

if (!isset($_GET["id_patient"])) {
    die("Patient introuvable.");
}

$id_patient = intval($_GET["id_patient"]);
$message = "";

/* RÉCUPÉRER LE PATIENT */
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

/* UPLOAD IMAGE PAR MÉDECIN */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (!isset($_FILES["image"])) {
        $message = "Aucune image reçue.";
    } elseif ($_FILES["image"]["error"] !== 0) {
        $message = "Erreur upload image. Code erreur : " . $_FILES["image"]["error"];
    } else {
        $nomOriginal = basename($_FILES["image"]["name"]);
        $extension = strtolower(pathinfo($nomOriginal, PATHINFO_EXTENSION));

        $allowed = ["jpg", "jpeg", "png"];

        if (!in_array($extension, $allowed)) {
            $message = "Format non autorisé. Utilisez JPG, JPEG ou PNG.";
        } else {

            $dossier = "../uploads/peau/";

            if (!is_dir($dossier)) {
                mkdir($dossier, 0777, true);
            }

            $fileName = time() . "_" . uniqid() . "." . $extension;

            $targetPath = $dossier . $fileName;
            $dbPath = "uploads/peau/" . $fileName;

            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetPath)) {

                $sql = "INSERT INTO images_peau (id_patient, image_path)
                        VALUES (:id_patient, :image_path)";

                $stmt = $db->prepare($sql);

                $stmt->execute([
                    ":id_patient" => $id_patient,
                    ":image_path" => $dbPath
                ]);

                $id_image = $db->lastInsertId();

                header("Location: ../ia/predict.php?id_image=" . $id_image);
                exit();

            } else {
                $message = "Impossible d'enregistrer l'image.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Analyse image patient - SkinCancer AI</title>
    <link rel="stylesheet" href="../css/style.css?v=20">
</head>
<body>

<div class="header">
    <div class="brand">
        <img src="../assets/logo.png" alt="SkinCancer AI" class="brand-logo">
        <strong>SkinCancer AI - Analyse image</strong>   
    </div>    
    
    <div>
        <a href="dossiers.php">Retour dossiers</a>
        <a href="dashboard.php">Accueil médecin</a>
        <a href="../logout.php">Déconnexion</a>
    </div>
</div>

<div class="container">

    <div class="hero">
        <h1>Analyse d’image cutanée</h1>
        <p>
            Importez une image de lésion cutanée du patient afin de générer une prédiction
            à l’aide du modèle d’intelligence artificielle TensorFlow/Keras.
        </p>
    </div>

    <div class="card">
        <h2>Patient sélectionné</h2>

        <p><strong>Nom :</strong> 
            <?php echo htmlspecialchars($patient["nom"] . " " . $patient["prenom"]); ?>
        </p>

        <p><strong>Email :</strong> 
            <?php echo htmlspecialchars($patient["email"]); ?>
        </p>

        <p><strong>Âge :</strong> 
            <?php echo htmlspecialchars($patient["age"]); ?>
        </p>

        <p><strong>Sexe :</strong> 
            <?php echo htmlspecialchars($patient["sexe"]); ?>
        </p>
    </div>

    <div class="card">
        <h2>Ajouter une image médicale</h2>

        <p>
            Formats acceptés : JPG, JPEG ou PNG. Le résultat obtenu constitue une aide
            au dépistage et doit être confirmé par un médecin ou un dermatologue.
        </p>

        <?php if (!empty($message)): ?>
            <p class="error">
                <?php echo htmlspecialchars($message); ?>
            </p>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <label>Image de lésion cutanée</label>
            <input type="file" name="image" id="imageInput" accept=".jpg,.jpeg,.png" required>

            <div class="image-preview-box">
                <p><strong>Aperçu de l’image :</strong></p>
                <img id="preview" class="preview" style="display:none;">
            </div>

            <button type="submit">Analyser avec IA</button>

            <a href="resultats_patient.php?id_patient=<?php echo $id_patient; ?>" class="btn btn-secondary">
                Voir résultats
            </a>
        </form>

        <div class="note">
            Le résultat généré par l’IA est indicatif. Il ne remplace pas un diagnostic médical officiel.
        </div>
    </div>

</div>

<script>
document.getElementById("imageInput").addEventListener("change", function(event) {
    const file = event.target.files[0];
    const preview = document.getElementById("preview");

    if (file) {
        const reader = new FileReader();

        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = "block";
        };

        reader.readAsDataURL(file);
    }
});
</script>

</body>
</html>