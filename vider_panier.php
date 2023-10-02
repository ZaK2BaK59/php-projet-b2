<?php
session_start();

// Vider le panier
$_SESSION['panier'] = [];

// Rediriger vers la page d'accueil ou une autre page
header('Location: accueil.php');
?>
