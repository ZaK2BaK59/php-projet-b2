<?php
session_start(); // Rrpend la session en cours.

if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Vérifie si la requête est de type POST.
    require_once('db.php'); // Inclut le fichier 'db.php' qui contient probablement les paramètres de connexion à la base de données.

    $username = $_POST['username']; // Récupère le nom d'utilisateur depuis le formulaire.
    $password = $_POST['password']; // Récupère le mot de passe depuis le formulaire.

    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE username = ?"); // Prépare une requête SQL pour récupérer l'utilisateur correspondant au nom d'utilisateur fourni.
    $stmt->execute([$username]); // Exécute la requête SQL avec le nom d'utilisateur.
    $user = $stmt->fetch(); // Récupère le résultat de la requête (l'utilisateur) et le stocke dans la variable $user.

    if ($user && password_verify($password, $user['password'])) { // Vérifie si l'utilisateur existe et si le mot de passe est correct (après le hachage).
        $_SESSION['loggedin'] = true; // Définit la session comme étant connectée.
        $_SESSION['username'] = $username; // Stocke le nom d'utilisateur dans la session.
        $_SESSION['rank'] = $user['rank']; // Ajoute le rang de l'utilisateur à la session.

        header('Location: accueil.php'); // Redirige vers la page d'accueil après une connexion réussie.
        exit(); // Termine l'exécution du script.
    } else {
        $error_message = "Nom d'utilisateur ou mot de passe incorrect"; // Message d'erreur en cas d'informations d'identification incorrectes.
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Formulaire de Connexion</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="login-box">
        <h2>Connexion</h2>
        <form action="" method="post">
            <div class="textbox">
                <input type="text" name="username" placeholder="Nom d'utilisateur">
            </div>
            <div class="textbox">
                <input type="password" name="password" placeholder="Mot de passe">
            </div>
            <input type="submit" class="btn" value="Se Connecter">
        </form>
        <?php
        if (isset($error_message)) {
            echo '<div class="error">' . $error_message . '</div>';
        }
        ?>
         <p>Vous n'avez pas de compte ? <a href="inscription.php">Créez un compte ici</a>.</p>
    </div>
</body>
</html>
