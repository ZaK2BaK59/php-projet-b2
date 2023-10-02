<?php
session_start();

require_once('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['supprimer_produit'])) {
    $produit_id = $_POST['produit_id'];

    // Supprimer le produit de la base de données
    $stmt = $pdo->prepare("DELETE FROM produits WHERE id = ?");
    $stmt->execute([$produit_id]);
    header('Location: suppression_produits.php');
    exit();
}

$stmt = $pdo->query("SELECT * FROM produits");
$produits = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Suppression de Produits</title>
    <link rel="stylesheet" href="styles_suppression_produits.css">
</head>
<body>
    <div class="header">
        <h1>Suppression de Produits</h1>
        <a href="accueil.php" class="retour">Retour à l'Accueil</a>
    </div>

    <h2>Liste des Produits</h2>
    <ul class="produits-list">
        <?php foreach ($produits as $produit): ?>
            <li>
                <?= $produit['nom'] ?> 
                <form action="" method="post" class="delete-form">
                    <input type="hidden" name="produit_id" value="<?= $produit['id'] ?>">
                    <input type="submit" name="supprimer_produit" value="Supprimer">
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
