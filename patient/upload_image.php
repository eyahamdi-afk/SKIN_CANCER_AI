<?php
session_start();
require_once "../config/connexion.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "patient") {
    header("Location: ../login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();

$message = "";

/* RÉCUPÉRER ID PATIENT */
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

/* UPLOAD IMAGE */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (isset($_FILES["image"]) && $_FILES["image"]["error"] === 0) {

        $extension = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
        $allowed = ["jpg", "jpeg", "png"];

        if (in_array($extension, $allowed)) {

            $uploadDir = "../uploads/peau/";

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $fileName = time() . "_" . uniqid() . "." . $extension;

            $targetPath = $uploadDir . $fileName;
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
                $message = "Erreur lors de l’enregistrement de l’image.";
            }

        } else {
            $message = "Format non autorisé. Utilisez JPG, JPEG ou PNG.";
        }

    } else {
        $message = "Veuillez choisir une image.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Upload Image - SkinCancer AI</title>
    <link rel="stylesheet" href="../css/style.css?v=20">
</head>
<body>

<div class="header">
    <div class="brand">
        <img src="assets/logo.png" alt="SkinCancer AI" class="brand-logo">
       <strong>SkinCancer AI - Upload Image</strong>
    </div>

    <div>
        <a href="dashboard.php">Accueil patient</a>
        <a href="../logout.php">Déconnexion</a>
    </div>
</div>

<div class="container">

    <div class="hero">
        <h1>Envoyer une image de lésion cutanée</h1>
        <p>
            Importez une image médicale au format JPG, JPEG ou PNG afin de lancer
            l’analyse par intelligence artificielle.
        </p>
    </div>

    <div class="card">
        <h2>Image cutanée</h2>

        <?php if (!empty($message)): ?>
            <p class="error">
                <?php echo htmlspecialchars($message); ?>
            </p>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <label>Image de la lésion cutanée</label>
            <input type="file" name="image" id="imageInput" accept=".jpg,.jpeg,.png" required>

            <div class="image-preview-box">
                <p><strong>Aperçu de l’image :</strong></p>
                <img id="preview" class="preview" style="display:none;">
            </div>

            <button type="submit">Analyser avec IA</button>
            <a href="dashboard.php" class="btn btn-secondary">Retour</a>
        </form>

        <div class="note">
            Le résultat généré par l’IA est indicatif. Il ne remplace pas un diagnostic médical
            et doit être confirmé par un médecin ou un dermatologue.
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