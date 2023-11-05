<?php
session_start();
$_SESSION['page_avant_login'] = $_SERVER['REQUEST_URI'];

require('./util/panier.php');

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['clientID'])) {
    header("Location: login.php");
    exit;
}
// Assurez-vous que l'utilisateur est connecté (vous devrez ajouter la gestion de la session).
$clientID = $_SESSION['clientID'];

// Récupérez l'historique des commandes de l'utilisateur avec les détails des produits.
$historiqueCommandes = getHistoriqueCommandesAvecProduits($clientID);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Commandes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
</head>
<body>
<div class="container mt-5">
    <h1>Mes Commandes</h1>
    <table class="table">
        <thead>
        <tr>
            <th>Numéro de Commande</th>
            <th>Date de Commande</th>
            <th>Statut</th>
            <th>Détails de la Commande</th>
        </tr>
        </thead>
        <tbody>
        <?php if (empty($historiqueCommandes)) : ?>

                <td colspan="4"  style="text-align: center">Vous n'avez pas encore passé de commandes.</td>
            </tr>
        <?php endif; ?>
        <?php foreach ($historiqueCommandes as $commande) : ?>
            <tr>
                <td><?= $commande['commandeid'] ?></td>
                <td><?= $commande['datecommande'] ?></td>
                <td><?= $commande['statut'] ?></td>
                <td>
                    <a href="detailsCommande.php?commandeID=<?= $commande['commandeid'] ?>" class="btn btn-primary">Voir les détails</a>
                </td>
            </tr>
        <?php endforeach; ?>

        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
</body>
</html>
