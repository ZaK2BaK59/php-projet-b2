<?php
session_start(); // Reprend la session en cours.

// Vérifier si la requête est de type POST et si le bouton 'ajouter_panier' a été soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ajouter_panier'])) {
    $produit_id = $_POST['produit_id']; // Récupère l'ID du produit depuis le formulaire.
    $quantite = $_POST['quantite']; // Récupère la quantité depuis le formulaire.

    // Vérifier si le produit est déjà dans le panier
    if (isset($_SESSION['panier'][$produit_id])) {
        $_SESSION['panier'][$produit_id] += $quantite; // Si oui, ajoute la nouvelle quantité à la quantité existante.
    } else {
        $_SESSION['panier'][$produit_id] = $quantite; // Sinon, ajoute le produit au panier avec la quantité spécifiée.
    }
    header('Location: accueil.php'); // Redirige vers la page d'accueil après l'ajout au panier.
    exit(); // Termine l'exécution du script.
}

// Vérifier si la requête est de type POST et si le bouton 'retirer_produit' a été soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['retirer_produit'])) {
    $produit_id = $_POST['produit_id']; // Récupère l'ID du produit depuis le formulaire.

    // Retirer le produit du panier
    unset($_SESSION['panier'][$produit_id]); // Supprime le produit du panier.
    header('Location: panier.php'); // Redirige vers la page du panier après le retrait du produit.
    exit(); // Termine l'exécution du script.
}

$pdo = new PDO('mysql:host=localhost;dbname=site', 'root', ''); // Établit une connexion à la base de données.
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Configure les attributs de la connexion.

$panier = []; // Initialise un tableau pour stocker les produits du panier.
if (isset($_SESSION['panier'])) {
    foreach ($_SESSION['panier'] as $produit_id => $quantite) { // Parcourt les produits dans le panier.
        $stmt = $pdo->prepare("SELECT * FROM produits WHERE id = ?");
        $stmt->execute([$produit_id]);
        $produit = $stmt->fetch(PDO::FETCH_ASSOC); // Récupère les détails du produit.

        if ($produit) {
            $produit['quantite'] = $quantite; // Ajoute la quantité du produit.
            $panier[$produit_id] = $produit; // Stocke le produit dans le panier.
        }
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Panier</title>
    <link rel="stylesheet" href="styles_panier.css">
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('acheter').addEventListener('click', function() {
                if (confirm("Merci pour votre achat !")) {
                    window.location.href = 'vider_panier.php';
                }
            });
        });
    </script>
</head>
<body>
    <div class="header">
        <h1>Votre Panier</h1>
        <a href="accueil.php" class="retour">Retour à l'Accueil</a>
    </div>

    <?php if (empty($panier)): ?>
    <p>Votre panier est vide.</p>
    <?php else: ?> <!-- Si la condition précédente est fausse -->
    <div class="panier"> <!-- Début de la section du panier -->
        <?php foreach ($panier as $produit_id => $produit): ?> <!-- Pour chaque produit dans le panier -->
            <div class="produit-panier"> <!-- Début de la section d'un produit dans le panier -->
                <h3><?= $produit['nom'] ?></h3> <!-- Affiche le nom du produit -->
                <p>Prix : <?= $produit['prix'] ?> €</p> <!-- Affiche le prix du produit -->
                <p>Quantité : <?= $produit['quantite'] ?></p> <!-- Affiche la quantité du produit -->
                <p>Prix total : <?= $produit['prix'] * $produit['quantite'] ?> €</p> <!-- Calcule et affiche le prix total du produit -->
                <form action="" method="post" class="delete-form"> <!-- Formulaire pour retirer un produit du panier -->
                    <input type="hidden" name="produit_id" value="<?= $produit_id ?>"> <!-- Champ caché contenant l'ID du produit à retirer -->
                    <button type="submit" name="retirer_produit">&#128465; Retirer</button> <!-- Bouton pour retirer le produit du panier -->
                </form>
            </div> <!-- Fin de la section d'un produit dans le panier -->
        <?php endforeach; ?> <!-- Fin de la boucle pour chaque produit -->
    </div> <!-- Fin de la section du panier -->

    <h3 id="total">Total : <?= array_sum(array_map(function($produit) { return $produit['prix'] * $produit['quantite']; }, $panier)) ?> €</h3> <!-- Affiche le total du panier -->
    <button id="acheter">Acheter</button> <!-- Bouton "Acheter" -->
<?php endif; ?> <!-- Fin de la condition (si l'utilisateur est connecté ou non) -->



</body>
</html>
