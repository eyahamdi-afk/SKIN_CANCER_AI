<?php
session_start();
require_once "../config/connexion.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "medecin") {
    header("Location: ../login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = trim($_POST["nom"]);
    $prenom = trim($_POST["prenom"]);
    $email = trim($_POST["email"]);
    $password = password_hash("123456", PASSWORD_DEFAULT);

    $age = $_POST["age"];
    $sexe = $_POST["sexe"];
    $telephone = trim($_POST["telephone"]);
    $adresse = trim($_POST["adresse"]);

    try {
        $sql = "INSERT INTO users (nom, prenom, email, password, role)
                VALUES (:nom, :prenom, :email, :password, 'patient')";

        $stmt = $db->prepare($sql);
        $stmt->execute([
            ":nom" => $nom,
            ":prenom" => $prenom,
            ":email" => $email,
            ":password" => $password
        ]);

        $id_user = $db->lastInsertId();

        $sql = "INSERT INTO patients (id_user, age, sexe, telephone, adresse)
                VALUES (:id_user, :age, :sexe, :telephone, :adresse)";

        $stmt = $db->prepare($sql);
        $stmt->execute([
            ":id_user" => $id_user,
            ":age" => $age,
            ":sexe" => $sexe,
            ":telephone" => $telephone,
            ":adresse" => $adresse
        ]);

        $message = "Patient ajouté avec succès. Mot de passe par défaut : 123456";

    } catch (PDOException $e) {
        if ($e->getCode() == 23505) {
            $message = "Erreur : cet email est déjà utilisé.";
        } else {
            $message = "Erreur : " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ajouter patient - SkinCancer AI</title>
    <link rel="stylesheet" href="../css/style.css?v=20">
</head>
<body>

<div class="header">
    <div class="brand">
        <img src="../assets/logo.png" alt="SkinCancer AI" class="brand-logo">
        <strong>SkinCancer AI - Ajouter patient</strong>   
    </div>    
    
    <div>
        <a href="dashboard.php">Accueil médecin</a>
        <a href="dossiers.php">Dossiers patients</a>
        <a href="../logout.php">Déconnexion</a>
    </div>
</div>

<div class="container">

    <div class="hero">
        <h1>Créer un dossier patient</h1>
        <p>
            Ajoutez un nouveau patient et créez automatiquement son dossier médical
            pour le suivi des images cutanées et des analyses IA.
        </p>
    </div>

    <div class="card">
        <h2>Informations personnelles</h2>

        <div class="note">
            Mot de passe initial du patient : <strong>123456</strong>. 
            Il pourra être changé ultérieurement.
        </div>

        <?php if (!empty($message)): ?>
            <p class="<?php echo strpos($message, 'Erreur') === false ? 'success' : 'error'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </p>
        <?php endif; ?>

        <form method="POST">

            <label>Nom</label>
            <input type="text" name="nom" required>

            <label>Prénom</label>
            <input type="text" name="prenom" required>

            <label>Email</label>
            <input type="email" name="email" required>

            <label>Âge</label>
            <input type="number" name="age" min="1" max="120" required>

            <label>Sexe</label>
            <select name="sexe" required>
                <option value="">-- Choisir --</option>
                <option value="Femme">Femme</option>
                <option value="Homme">Homme</option>
            </select>

            <label>Téléphone</label>
            <input type="text" name="telephone" placeholder="Exemple : 24700567">

            <label>Adresse</label>
            <textarea name="adresse" rows="4" placeholder="Adresse du patient"></textarea>

            <button type="submit">Créer le dossier patient</button>

            <a href="dossiers.php" class="btn btn-secondary">
                Retour aux dossiers
            </a>

        </form>
    </div>

</div>

</body>
</html>