<?php
session_start();
require_once "config/connexion.php";

$database = new Database();
$db = $database->getConnection();

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    $sql = "SELECT * FROM users WHERE email = :email";
    $stmt = $db->prepare($sql);
    $stmt->execute([
        ":email" => $email
    ]);

    $user = $stmt->fetch();

    if ($user && password_verify($password, $user["password"])) {
        $_SESSION["user_id"] = $user["id_user"];
        $_SESSION["nom"] = $user["nom"];
        $_SESSION["prenom"] = $user["prenom"];
        $_SESSION["role"] = $user["role"];

        if ($user["role"] === "patient") {
            header("Location: patient/dashboard.php");
            exit();
        } elseif ($user["role"] === "medecin") {
            header("Location: medecin/dashboard.php");
            exit();
        } else {
            $message = "Rôle utilisateur invalide.";
        }
    } else {
        $message = "Email ou mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Connexion - SkinCancer AI</title>
    <link rel="stylesheet" href="css/style.css?v=20">
</head>
<body>

<div class="header">
    <div class="brand">
        <img src="assets/logo.png" alt="SkinCancer AI" class="brand-logo">
       <strong>SkinCancer AI - Connexion</strong>
    </div>
    
    <div>
        <a href="index.php">Accueil</a>
        <a href="inscription.php">Inscription</a>
    </div>
</div>

<div class="container">

    <div class="hero">
        <h1>Connexion à la plateforme</h1>
        <p>
            Accédez à votre espace patient ou médecin pour consulter les analyses
            des images cutanées et gérer les dossiers médicaux.
        </p>
    </div>

    <div class="card">
        <h2>Authentification</h2>

        <?php if (!empty($message)): ?>
            <p class="error">
                <?php echo htmlspecialchars($message); ?>
            </p>
        <?php endif; ?>

        <form method="POST" autocomplete="off">
            <label>Email</label>
            <input type="email" name="email" required autocomplete="off">

            <label>Mot de passe</label>
            <input type="password" name="password" required autocomplete="new-password">

            <button type="submit">Se connecter</button>

            <a href="inscription.php" class="btn btn-secondary">
                Créer un compte
            </a>
        </form>
    </div>

</div>

</body>
</html>