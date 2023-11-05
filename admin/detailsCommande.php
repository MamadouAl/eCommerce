<?php
session_start();
$_SESSION['page_avant_login'] = $_SERVER['REQUEST_URI'];

require('../util/panier.php');

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['clientID'])) {
    header("Location: login.php");
    exit;
}
// Assurez-vous que l'utilisateur est connecté (vous devrez ajouter la gestion de la session).
$clientID = $_SESSION['clientID'];
// Récupérez l'ID de la commande à afficher depuis la requête (vous pouvez le transmettre via un paramètre d'URL).
if (isset($_GET['commandeID'])) {
    $commandeID = intval($_GET['commandeID']);

    // Récupérez les détails de la commande spécifique.
    $commandeDetails = getCommandeDetails($commandeID);

    // Assurez-vous que la commande appartient à l'utilisateur actuellement connecté.
    if ($commandeDetails['clientid'] == $clientID) {
        // Récupérez les produits inclus dans cette commande.
        $produitsCommande = $commandeDetails['produits'];
    } else {
        // Redirigez l'utilisateur vers une page d'erreur ou de gestion des autorisations.
        header("Location: erreur.php");
        exit();
    }
} else {
    // Redirigez l'utilisateur vers une page d'erreur.
    header("Location: erreur.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de la Commande</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
</head>
<body>
<div class="container mt-5">
    <h1>Détails de la Commande</h1>
    <p>Numéro de Commande : <?= $commandeDetails['commandeid'] ?></p>
    <p>Date de Commande : <?= $commandeDetails['datecommande'] ?></p>
    <h5 >Statut : <b style="color: green"><?= $commandeDetails['statut'] ?></b></h5>

    <h2>Produits inclus dans la commande</h2>
    <table class="table">
        <thead>
        <tr>
            <th>Nom du Produit</th>
            <th>Description</th>
            <th>Prix</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($produitsCommande as $produit) : ?>
            <tr>
                <td><?= $produit['nom'] ?></td>
                <td><?= $produit['description'] ?></td>
                <td><?= $produit['prix'] ?> €</td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
</body>
</html>