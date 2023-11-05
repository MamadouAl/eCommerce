<?php
session_start();
$_SESSION['page_avant_login'] = $_SERVER['REQUEST_URI'];

require('./util/panier.php'); // Inclure votre fichier de fonctions pour le panier

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['clientID'])) {
    header("Location: login.php"); // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    exit;
}

$clientID = $_SESSION['clientID'];

// Créez un panier pour l'utilisateur s'il n'en a pas déjà ungetUserContenuPanier
$panierID = getPanierIDByClientID($clientID);
if (!$panierID || $panierID == 0) {
    $panierID = creePanier($clientID);
}

// Récupérez le contenu du panier de l'utilisateur
$contenuPanier = getUserContenuPanier($clientID);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['modifier_quantite'])) {
        foreach ($_POST['quantite'] as $produitID => $nouvelleQuantite) {
            // Mettez à jour la quantité d'un produit dans le panier
            updateQuantitePanierProduit($panierID, $produitID, $nouvelleQuantite);
        }
    }
    if (isset($_POST['supprimer_produit'])) {
        // Supprimez un produit du panier
        $produitID =($_POST['produitID']); // Obtenir la clé du produit à supprimer
        deleteProPanier($panierID, $produitID);
    }
    header("Location: #");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Panier de l'Utilisateur</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <!-- Bootstrap 4 CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
          integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO"
          crossorigin="anonymous">

    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"
          integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU"
          crossorigin="anonymous">
</head>
<body>

<header>
    <?php include('./includes/header.php'); //  ?>
</header>
<div class="container">
    <div class="d-flex justify-content-center h-110">
        <div class="card">
            <div class="card-header">
                <h3>Votre Panier</h3>
            </div >
            <div class="card-body">
                <div class="row">
                    <div class="col-md-10">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Produit</th>
                                <th>Nom</th>
                                <th>Description</th>
                                <th>Prix</th>
                                <th>Quantité</th>
                                <th colspan="2">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (empty($contenuPanier)) : ?>
                                <tr>
                                    <td colspan="7" style="text-align: center">Votre panier est vide.</td>
                                </tr>
                            <?php endif; ?>

                            <?php foreach ($contenuPanier as $produit) : ?>
                                <form method="post">
                                    <tr><a>
                                        <td> <a href="afficherUnProduit.php?produitid=<?=$produit['produitid'] ?>">
                                            <img src="<?= $produit['image_url'] ?>" alt="<?= $produit['nom'] ?>" style="max-width: 100px; height: auto;"/></td>
                                            </a>
                                        <td><?= $produit['nom'] ?></td>
                                        <td><?= $produit['description'] ?></td>
                                        <td><?= $produit['prix'] ?> €</td>

                                        <td>
                                            <label>
                                                <input type="number" name="quantite[<?= $produit['produitid'] ?>]" value="<?= $produit['quantite'] ?>" min="1">
                                            </label>
                                            <input type="hidden" name="produitID" value="<?= $produit['produitid'] ?>">

                                        </td>
                                        <td>
                                            <button type="submit" name="modifier_quantite" class="btn btn-primary">Modifier</button>
                                        </td>
                                        <td>
                                            <button type="submit" name="supprimer_produit" class="btn btn-link" style="padding: 0;">
                                                <i class="fas fa-trash" style="color: red; font-size: 20px;"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </form>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-8" >
                        <h4>Total du Panier</h4>
                        <h2>
                            <?php
                            $total = 0;
                            foreach ($contenuPanier as $produit) {
                                $total += $produit['prix'] * $produit['quantite'];
                            }
                            echo "<b>".$total . " €"."</b>";
                            ?>
                        </h2>
                        <a href="index.php" class="btn btn-secondary">Continuer les achats</a>
                        <a href="passerUneCommande.php?panierid=<?=$panierID ?>" class="btn btn-success mt-2">Passer  commande</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
