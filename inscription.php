<?php
session_start();

// Vérifier si l'utilisateur est déjà connecté, le rediriger vers la page d'accueil
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header('Location: accueil.php');
    exit();
}

// Si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['creer_compte'])) {
    // Connectez-vous à la base de données
    $pdo = new PDO('mysql:host=localhost;dbname=site', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupérer les valeurs du formulaire
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hasher le mot de passe
    $rank = 'user'; // Par défaut, un nouvel utilisateur a le rang "user"

    // Préparer et exécuter la requête d'insertion
    $stmt = $pdo->prepare("INSERT INTO utilisateurs (username, password, rank) VALUES (?, ?, ?)");
    $stmt->execute([$username, $password, $rank]);

    // Rediriger vers la page d'accueil ou autre page
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer un Compte</title>
    <link rel="stylesheet" href="inscription.css">
</head>
<body>
    <h1>Créer un Compte</h1>
    <form action="" method="post">
        <label for="username">Nom d'Utilisateur :</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Mot de Passe :</label>
        <input type="password" id="password" name="password" required>

        <button type="submit" name="creer_compte">Créer un Compte</button>
    </form>

    <p>Déjà un compte ? <a href="login.php">Connectez-vous ici</a>.</p>
</body>
</html>
