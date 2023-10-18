<?php
session_start();
require('./util/panier.php'); // Inclure votre fichier de fonctions pour le panier

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['clientID'])) {
    header("Location: login.php"); // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    exit;
}

$clientID = $_SESSION['clientID'];

// Créez un panier pour l'utilisateur s'il n'en a pas déjà un
$panierID = getPanierIDByClientID($clientID);
if (!$panierID || $panierID==0 ) {
    $panierID = creePanier($clientID);
}

// Récupérez le contenu du panier de l'utilisateur
$contenuPanier = getUserContenuPanier($clientID);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (isset($_POST['modifier_quantite'])) {
        // Mettez à jour la quantité d'un produit dans le panier
        $panierID = $_POST['panierID'];
        $quantite = $_POST['quantite'];
        updateQuantitePanier($panierID, $quantite);
    } elseif (isset($_POST['panierID'])) {
        // Supprimez un produit du panier
        $produit_panierID = $_POST['panierID'];
        deleteProPanier($panierID, $produit_panierID);
    }
    // Redirigez pour actualiser la page après la modification du panier
    header("Location: client.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Panier de l'Utilisateur</title>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    
    <!--Bootsrap 4 CDN-->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    
    <!--Fontawesome CDN-->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
</head>
<body>
<div class="container">
    <div class="d-flex justify-content-center h-100">
        <div class="card">
            <div class="card-header">
                <h3>Votre Panier</h3>
            </div>
            <div class="card-body">
                <?php if (!empty($contenuPanier)) : ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Produit</th>
                                <th>Description</th>
                                <th>Prix</th>
                                <th>Quantité</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($contenuPanier as $produit) : ?>
                                <form method="post">
                                    <tr>
                                        <td><?= $produit['nom'] ?></td>
                                        <td><?= $produit['description'] ?></td>
                                        <td><?= $produit['prix'] ?> €</td>
                                        <td>
                                            <input type="number" name="quantite" value="<?= $produit['quantite'] ?>" min="1">
                                            <input type="hidden" name="panierID" value="<?= $produit['panierid'] ?>">
                                        </td>
                                        <td>
                                            <button type="submit" name="modifier_quantite" class="btn btn-primary">Modifier</button>
                                            <button type="submit" name="supprimer_produit" class="btn btn-danger">Supprimer</button>
                                        </td>
                                    </tr>
                                </form>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else : ?>
                    <p>Votre panier est vide.</p>
                <?php endif; ?>
                <a href="index.php" class="btn btn-secondary">Continuer les achats</a>
                <a href="passer_commande.php" class="btn btn-success">Passer commande</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
