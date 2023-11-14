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
    <title><?= $user['nom'].' '.$user['prenom'] ?> </title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #iciProfil {
            color: white;
            border-bottom: solid;
        }
        .profile-content {
            font-size: large;
            margin-left: 10px;
        }
    </style>
</head>
<body>
<header>
    <?php include('./includes/monHeader.php'); //  ?>

    <div style="text-align: center; background-color: darkgrey; margin-top: 0; padding: 20px;">
        <h1 style="color: chocolate; display: inline;"><?=$user['nom'].' '.$user['prenom']?></h1>
    </div>
</header>


<div class="container">
    <div class="row profile">
        <div class="col-md-3">
            <div class="profile-sidebar">
                <div class="profile-userpic">
                    <!-- la photo de profil de l'utilisateur -->
                    <img src="<?= "https://static.thenounproject.com/png/363640-200.png"//$user['photo_url'] ?>" class="img-responsive" alt="">
                </div>
                <div class="profile-usertitle">
                    <div class="profile-usertitle-name">
                        <?= $user['prenom'] ?> <?= $user['nom'] ?>
                    </div>
                    <div class="profile-usertitle-job">
                        <?php if(isset($_SESSION['admin'])) echo '<b>Admin</b>'; else echo
                        '<b>Client</b>'
                        ?>
                    </div>
                </div>
                <div class="profile-userbuttons">
                    <button type="button" class="btn btn-success btn-sm">Follow</button>
                    <button type="button" class="btn btn-danger btn-sm">Message</button>
                </div>
                <div class="profile-usermenu">
                    <ul class="nav">
                        <li class="active">
                            <a href="index.php">
                                <i class="glyphicon glyphicon-home"></i>
                                Accueil
                            </a>
                        </li>
                        <li>
                            <a href="modifProfil.php">
                                <i class="glyphicon glyphicon-user"></i>
                                Modifier mon profil
                            </a>
                        </li>
                        <li>
                            <a href="mesCommandes.php">
                                <i class="glyphicon glyphicon-ok"></i>
                                Mes commandes
                            </a>
                        </li>
                        <li>
                            <a href="monPanier.php">
                                <i class="glyphicon glyphicon-flag"></i>
                                Voir mon panier
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="profile-content">
                <h3>Informations du Client</h3>
                <p>Nom: <?= $user['prenom'] ?> <?= $user['nom'] ?></p>
                <p>Email: <?= $user['email'] ?></p>
                <!-- Affichez d'autres informations du client ici -->

                <h3>Historique des Commandes</h3>
                <ul>
                    <?php
                    $historiqueUtilisateur = getHistoriqueCommandesAvecProduits($clientID);

                    if( empty($historiqueUtilisateur) ) {
                        echo "<p>Vous n'avez pas encore passé de commandes.</p>";
                    }
                    foreach ($historiqueUtilisateur as $commande) :
                        ?>
                        <li><b> Commande <?= $commande['commandeid'] ?> - Date: <?= $commande['datecommande'] ?></b>
                            <ul>
                                <?php foreach ($commande['produits'] as $produit) : ?>
                                    <li><a href="afficherUnProduit.php?produitid=<?=$produit['produitid'] ?>">
                                        <img src="<?= $produit['image_url'] ?>" alt=" <?= $produit['nom'] ?>" style="max-width: 100px; height: auto;"/> </a>
                                            <?= $produit['nom'] ?>
                                            - <?= $produit['description'] ?>
                                            - <?= $produit['prix'] ?>

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
</body>

</html>
