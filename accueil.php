<?php
session_start(); // Reprend la session en cours

require_once('db.php'); // Inclut le fichier 'db.php'(paramètres de log a la bdd)

$sortQuery = ''; // Initialise une variable pour le tri, mais elle reste vide ici. (sinon bug d'affichage totale)

// Suppression d'un produit
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['supprimer_produit'])) { // Vérifie si la requête est de type POST et si la variable 'supprimer_produit' est définie.
    $produit_id = $_POST['produit_id']; // Récupère l'ID du produit à supprimer depuis le formulaire.

    // Supprimer le produit de la base de données
    $stmt = $pdo->prepare("DELETE FROM produits WHERE id = ?"); // Prépare une requête SQL pour supprimer un produit avec l'ID correspondant.
    $stmt->execute([$produit_id]); // Exécute la requête SQL avec l'ID du produit.
    header('Location: accueil.php'); // Redirige vers la page d'accueil après la suppression.
    exit(); // Termine l'exécution du script.
}

// Filtre par type
$type = isset($_GET['type']) ? $_GET['type'] : ''; // Vérifie s'il y a un paramètre 'type' dans l'URL, sinon le met à vide.
$filterQuery = ''; // Initialise une variable pour le filtrage, mais elle reste vide ici.

if ($type === 'fruit') { // Vérifie si le type est 'fruit'.
    $filterQuery = " AND type = 'fruit'"; // Ajoute une condition de filtrage pour les fruits.
} elseif ($type === 'legume') { // Vérifie si le type est 'legume'.
    $filterQuery = " AND type = 'legume'"; // Ajoute une condition de filtrage pour les légumes.
}

$stmt = $pdo->query("SELECT * FROM produits WHERE 1" . $filterQuery . $sortQuery); // Exécute une requête SQL pour sélectionner les produits en utilisant les conditions de filtrage et de tri.
$produits = $stmt->fetchAll(); // Récupère tous les résultats de la requête SQL et les stocke dans une variable.
?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil</title>
    <link rel="stylesheet" href="styles_accueil.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
</head>
<body>
    <div class="header">
        <h1>Les fruits frais</h1>
        <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
            <?php if ($_SESSION['rank'] === 'admin' || $_SESSION['rank'] === 'superadmin'): ?>
                <a href="ajouter_stock.php" class="ajouter-stock">Ajouter Stock</a>
                <a href="stock_zero.php" class="stock_zero">Stock Zero</a>
            <?php endif; ?>

            <?php if ($_SESSION['rank'] === 'superadmin'): ?>
                <a href="ajouter_produit.php" class="ajouter-produit">Ajouter Produit</a>
                <a href="suppression_produits.php" class="suppression-produits">Suppression Produits</a>
            <?php endif; ?>
            <a href="panier.php" class="deconnexion">Panier</a>
            <a href="deconnexion.php" class="deconnexion">Déconnexion</a>
        <?php endif; ?>
    </div>

    <h2>Nos Produits</h2>
    <form action="accueil.php" method="get" class="filtre-form">
    <label>
        <input type="radio" name="type" value="fruit" <?= (isset($_GET['type']) && $_GET['type'] === 'fruit') ? 'checked' : '' ?>>
        Fruits
    </label>
    <label>
        <input type="radio" name="type" value="legume" <?= (isset($_GET['type']) && $_GET['type'] === 'legume') ? 'checked' : '' ?>>
        Légumes
    </label>
    <button type="submit">Filtrer</button>
    </form>
    <div class="produits">
        <?php foreach ($produits as $produit): ?>
            <div class="produit">
                <form action="panier.php" method="post">
                    <h3><?= $produit['nom'] ?></h3>
                    <p>Prix : <?= $produit['prix'] ?> €</p>
                    <p>Stock disponible : <?= $produit['stock'] ?></p>
                    <?php if ($produit['stock'] > 0): ?>
                        <p style="color: green;">Disponible</p>
                        <label for="quantite">Quantité :</label>
                        <input type="number" id="quantite" name="quantite" value="1" min="1" max="<?= $produit['stock'] ?>">
                        <input type="hidden" name="produit_id" value="<?= $produit['id'] ?>">
                        <input type="submit" name="ajouter_panier" value="Ajouter au Panier">
                    <?php else: ?>
                        <p style="color: red;">Indisponible</p>
                    <?php endif; ?>
                </form>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true): ?>
        <a href="login.php">Se Connecter</a>
    <?php endif; ?>
</body>
</html>
