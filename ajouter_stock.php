<?php
session_start(); // Reprend la session en cours.

if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Vérifie si la requête est de type POST.
    require_once('db.php'); // Inclut le fichier 'db.php' qui contient probablement les paramètres de connexion à la base de données.

    if (isset($_POST['ajouter_stock'])) { // Vérifie si le formulaire a été soumis avec le bouton 'ajouter_stock'.
        $produit_id = $_POST['produit_id']; // Récupère l'ID du produit depuis le formulaire.
        $quantite = $_POST['quantite']; // Récupère la quantité à ajouter au stock depuis le formulaire.

        // Mettez à jour le stock dans la base de données
        $stmt = $pdo->prepare("UPDATE produits SET stock = stock + ? WHERE id = ?"); // Prépare une requête SQL pour mettre à jour le stock d'un produit.
        $stmt->execute([$quantite, $produit_id]); // Exécute la requête SQL avec les valeurs récupérées depuis le formulaire.

        $success_message = "Stock ajouté avec succès."; // Message de succès après la mise à jour du stock.
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter du Stock</title>
    <link rel="stylesheet" href="styles_ajouter_stock.css">
</head>
<body>
<div class="header">
        <a href="accueil.php" class="retour">Retour à l'Accueil</a>
    </div>

    <div class="container">
        <h2>Ajouter du Stock</h2>
        <form action="" method="post">
            <div class="form-group">
                <label for="produit_id">Produit</label>
                <select name="produit_id" id="produit_id">
                    <?php
                    require_once('db.php');
                    $stmt = $pdo->query("SELECT * FROM produits");
                    $produits = $stmt->fetchAll();
                    foreach ($produits as $produit) {
                        echo '<option value="' . $produit['id'] . '">' . $produit['nom'] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="quantite">Quantité</label>
                <input type="number" name="quantite" id="quantite" min="-1" required>
            </div>
            <input type="submit" name="ajouter_stock" value="Ajouter Stock">
        </form>

        <?php
        if (isset($success_message)) {
            echo '<div class="success">' . $success_message . '</div>';
        }
        ?>
    </div>
</body>
</html>
