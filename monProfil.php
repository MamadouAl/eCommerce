<?php
session_start();
// Stockage l'URL actuelle dans une variable de session
$_SESSION['page_avant_login'] = $_SERVER['REQUEST_URI'];
include './util/users.php';  // Inclure les fonctions pour le panier

if (!isset($_SESSION['clientID'])) {
    header("Location: login.php");
}

if (empty($_SESSION['clientID'])) {
    header("Location: login.php");
}

$clientID = $_SESSION['clientID'];
$user = getUserByID($clientID);  // Fonction pour récupérer les informations du client
$commandes = getCommandesClient($clientID);  // Fonction pour récupérer l'historique des commandes
$panierID = getPanierIDByClientID($clientID);  // Fonction pour récupérer le panier

?>


<!DOCTYPE html>
<html lang="fr" data-bs-theme="auto">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="MamadouAl">
    <meta name="generator" content="Hugo 0.80.0">
    <title><?= $user['nom'] . ' ' . $user['prenom'] ?></title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="./CSS/header.css">

    <style>
        .container {
            margin-top: 15px;

        }
    </style>
</head>

<body>
<header>
    <?php include('./includes/monHeader.php'); ?>
</header>

<div class="container embed-responsive-16by9">
    <div class="row profile">
        <div class="col-md-5">
            <div class="card">
                <?php if(isset($user['profil_img']) && !empty($user['profil_img'])) : ?>
                    <img src="<?= $user['profil_img'] ?>" class="card-img-top" alt="Photo de profil">
                <?php else : ?>
                <img src="<?= "https://static.thenounproject.com/png/363640-200.png" ?>" class="card-img-top" alt="Photo de profil">
                <?php endif; ?>
                <div class="card-body">
                    <h5 class="card-title"><?= $user['prenom'] ?> <?= $user['nom'] ?></h5>
                    <p><strong>Email:</strong> <?= $user['email'] ?></p>

                    <p class="card-text">
                        <?php
                        if (($_SESSION['role'] === 'admin' )) {
                            echo '<b>Admin</b>';
                        } else {
                            echo '<b>Client</b>';
                        }
                        ?>
                    </p>
                    <div class="d-grid gap-2">
                        <?php if (($_SESSION['role'] === 'admin' )) : ?>
                            <a href="admin/admin.php" class="btn btn-danger btn-sm">Administration</a>
                        <!--<button type="button" href="admin/admin.php" class="btn btn-danger btn-sm">Administration</button>
                        --><?php endif; ?>
                        <button type="button" class="btn btn-success btn-sm">Follow</button>

                    </div>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <a href="index.php">
                            <i class="fas fa-home"></i> Accueil
                        </a>
                    </li>
                    <li class="list-group-item">
                        <a href="modifProfil.php">
                            <i class="fas fa-user"></i> Modifier mon profil
                        </a>
                    </li>
                    <li class="list-group-item">
                        <a href="mesCommandes.php">
                            <i class="fas fa-check"></i> Mes commandes
                        </a>
                    </li>
                    <li class="list-group-item">
                        <a href="monPanier.php">
                            <i class="fas fa-flag"></i> Voir mon panier
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="col-md-7" style="background-color:#f5f8ff">
            <div class="profile-content">
                <h2>Historique de vos commandes</h2><hr>
                <ul>
                    <?php
                    $historiqueUtilisateur = getHistoriqueCommandesAvecProduits($clientID);

                    if (empty($historiqueUtilisateur)) {
                        echo "<p>Vous n'avez pas encore passé de commandes.</p>";
                    }
                    foreach ($historiqueUtilisateur as $commande) :
                        if (empty($commande['produits'])) {
                            continue;
                        }
                        ?>
                        <li>
                            <strong>Commande du : <i><?= $commande['datecommande'] ?> </i></strong>
                            <ul>
                                <?php foreach ($commande['produits'] as $produit) : ?>
                                    <li>
                                        <a href="afficherUnProduit.php?produitid=<?= $produit['produitid'] ?>">
                                            <img src="<?= $produit['image_url'] ?>" alt="<?= $produit['nom'] ?>" style="max-width: 90px; height: auto;" />
                                        </a>
                                        <i><?= $produit['nom'] ?></i> - <?= $produit['prix'] ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <a href="#" class="btn btn-primary">Autres Options</a>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
</body>

</html>

