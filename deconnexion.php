<?php
session_start();

// Détruit toutes les données de session
session_unset();
session_destroy();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Déconnexion</title>
    <link rel="stylesheet" href="styles_deconnexion.css">
</head>
<body>
    <div class="container">
        <h2>Vous êtes déconnecté</h2>
        <p>Vous avez été déconnecté avec succès.</p>
        <a href="login.php" class="retour">Retour à la page de connexion</a>
    </div>
</body>
</html>
