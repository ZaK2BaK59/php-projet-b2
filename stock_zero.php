<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once('db.php');

    if (isset($_POST['mettre_zero'])) {
        $produit_id = $_POST['produit_id'];

        // Mettez à jour le stock dans la base de données
        $stmt = $pdo->prepare("UPDATE produits SET stock = 0 WHERE id = ?");
        $stmt->execute([$produit_id]);

        $success_message = "Stock mis à zéro avec succès.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mettre le Stock à Zéro</title>
    <link rel="stylesheet" href="stock_zero.css">
</head>
<body>
    <div class="header">
        <a href="accueil.php" class="retour">Retour à l'Accueil</a>
    </div>

    <div class="container">
        <h2>Mettre le Stock à Zéro</h2>
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
            <input type="submit" name="mettre_zero" value="Mettre à Zéro">
        </form>

        <?php
        if (isset($success_message)) {
            echo '<div class="success">' . $success_message . '</div>';
        }
        ?>
    </div>
</body>
</html>
