<?php
session_start();

require('./util/panier.php');

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['clientID'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['panierid'])) {
    header("Location: monPanier.php");
    exit;
}
$panierID = $_GET['panierid'];


// Votre code pour gérer l'ajout des produits du panier dans la base de données va ici
$clientID = $_SESSION['clientID'];
$contenuPanier = getUserContenuPanier($clientID);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['modifier_quantite'])) {
        $commandeID = passerCommande($clientID, $panierID);

        print_r($commandeID);
    }
}

//header("Location: #");
//exit;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <title>Récapitulatif de la Commande</title>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h2>Récapitulatif de la Commande</h2>
            <!-- Afficher les détails de la commande ici -->
            <table class="table">
                <thead>
                <tr>
                    <th>Produit</th>
                    <th>Description</th>
                    <th>Prix</th>
                    <th>Quantité</th>
                    <th>Montant</th>
                </tr>
                </thead>
                <tbody>

                <?php foreach ($contenuPanier as $produit) : ?>
                    <tr>
                    <td> <a href="monPanier.php ?>">
                            <img src="<?= $produit['image_url'] ?>" alt="<?= $produit['nom'] ?>" style="max-width: 100px; height: auto;"/>
                        </a>
                    </td>
                    <td><?= $produit['nom'] ?></td>
                    <td><?= $produit['description'] ?></td>
                    <td><?= $produit['quantite'] ?></td>
                    <td><?= $produit['prix'] * $produit['quantite']; ?> €</td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php
            $total = 0;
            foreach ($contenuPanier as $produit) {
                $total += $produit['prix'] * $produit['quantite'];
            }
            ?>

            <h3>Total de la Commande : <?="<b>".$total . " €"."</b>"?></h3>
            <form method="post">
                <input type="hidden" name="total" value="<?= $total?>">
                <button type="submit" name="modifier_quantite" class="btn btn-primary">Payer</button>
            </form>
            <a href="monPanier.php">
                <img src="./images/icon_panier.png" alt="Panier" width="60" height="60">
            </a>

        </div>
    </div>
</div>
</body>
</html>
