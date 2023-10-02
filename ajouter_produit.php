<?php
session_start(); // Reprend la session en cours

if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Vérifie si la requête est de type POST.
    require_once('db.php'); // Inclut le fichier 'db.php'(paramètres de log a la bdd)

    if (isset($_POST['ajouter_produit'])) { // Vérifie si le formulaire a été soumis avec le bouton 'ajouter_produit'.
        $nom = $_POST['nom']; // Récupère le nom du produit depuis le formulaire.
        $prix = $_POST['prix']; // Récupère le prix du produit depuis le formulaire.
        $stock = $_POST['stock']; // Récupère la quantité en stock du produit depuis le formulaire.
        $type = $_POST['type']; // Récupère le type du produit depuis le formulaire (ajouté récemment).

        // Insérez le nouveau produit dans la base de données
        $stmt = $pdo->prepare("INSERT INTO produits (nom, prix, stock, type) VALUES (?, ?, ?, ?)"); // Prépare une requête SQL pour insérer un nouveau produit.
        $stmt->execute([$nom, $prix, $stock, $type]); // Exécute la requête SQL avec les valeurs récupérées depuis le formulaire.

        $success_message = "Produit ajouté avec succès."; // Message de succès après l'ajout du produit.
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un Produit</title>
    <link rel="stylesheet" href="styles_ajouter_produit.css">
</head>
<body>
    <div class="header">
        <a href="accueil.php" class="retour">Retour à l'Accueil</a>
    </div>

    <div class="container">
        <h2>Ajouter un Produit</h2>
        <form action="" method="post">
            <div class="form-group">
                <label for="nom">Nom</label>
                <input type="text" name="nom" id="nom" required>
            </div>
            <div class="form-group">
                <label for="prix">Prix (€)</label>
                <input type="number" name="prix" id="prix" min="0.01" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="stock">Stock</label>
                <input type="number" name="stock" id="stock" min="0" required>
            </div>
            <input type="submit" name="ajouter_produit" value="Ajouter Produit">
            <div class="form-group">
                <label for="type">Type</label>
                <select name="type" id="type" required>
                    <option value="fruit">Fruit</option>
                    <option value="legume">Légume</option>
                </select>
            </div>

        </form>
        

        <?php
        if (isset($success_message)) {
            echo '<div class="success">' . $success_message . '</div>';
        }
        ?>
    </div>
</body>
</html>
