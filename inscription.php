<?php
session_start();
require_once "config/connexion.php";

$database = new Database();
$db = $database->getConnection();

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = trim($_POST["nom"]);
    $prenom = trim($_POST["prenom"]);
    $email = trim($_POST["email"]);
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $role = $_POST["role"];
    $age = $_POST["age"];
    $sexe = $_POST["sexe"];

    try {
        // Insérer dans la table users
        $sql = "INSERT INTO users (nom, prenom, email, password, role)
                VALUES (:nom, :prenom, :email, :password, :role)";

        $stmt = $db->prepare($sql);
        $stmt->execute([
            ":nom" => $nom,
            ":prenom" => $prenom,
            ":email" => $email,
            ":password" => $password,
            ":role" => $role
        ]);

        $id_user = $db->lastInsertId();

        // Si le compte est patient, créer aussi une ligne dans patients
        if ($role === "patient") {
            $sqlPatient = "INSERT INTO patients (id_user, age, sexe)
                           VALUES (:id_user, :age, :sexe)";

            $stmtPatient = $db->prepare($sqlPatient);
            $stmtPatient->execute([
                ":id_user" => $id_user,
                ":age" => $age,
                ":sexe" => $sexe
            ]);
        }

        $message = "Compte créé avec succès. Vous pouvez maintenant vous connecter.";

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
    <title>Inscription - SkinCancer AI</title>
    <link rel="stylesheet" href="css/style.css?v=20">
</head>
<body>

<div class="header">
    <div class="brand">
        <img src="assets/logo.png" alt="SkinCancer AI" class="brand-logo">
       <strong>SkinCancer AI - Inscription</strong>
    </div>    
    
    <div>
        <a href="index.php">Accueil</a>
        <a href="login.php">Connexion</a>
    </div>
</div>

<div class="container">

    <div class="hero">
        <h1>Créer un compte</h1>
        <p>
            Inscrivez-vous comme patient ou médecin pour accéder à la plateforme
            d’aide au dépistage du cancer de la peau par intelligence artificielle.
        </p>
    </div>

    <div class="card">
        <h2>Informations du compte</h2>

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

            <label>Mot de passe</label>
            <input type="password" name="password" required>

            <label>Âge</label>
            <input type="number" name="age" min="1" max="120" required>

            <label>Sexe</label>
            <select name="sexe" required>
                <option value="">-- Choisir --</option>
                <option value="Femme">Femme</option>
                <option value="Homme">Homme</option>
            </select>

            <label>Rôle</label>
            <select name="role" required>
                <option value="patient">Patient</option>
                <option value="medecin">Médecin</option>
            </select>

            <button type="submit">S'inscrire</button>

            <a href="login.php" class="btn btn-secondary">
                Déjà un compte ?
            </a>
        </form>
    </div>

</div>

</body>
</html>