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
<html lang="fr">

<head>
    <title><?= $user['nom'].' '.$user['prenom'] ?> </title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Votre CSS personnalisé va ici */
    </style>
</head>

<body>
<header>
    <div style="text-align: center; background-color: darkgrey; padding: 20px;">
        <h1 style="color: chocolate; display: inline;"><?=$user['nom'].' '.$user['prenom']?></h1>
        <b><a href="deconnexion.php" style="font-size: large; color: red; display: inline; margin-left: 65%;">Déconnexion</a></b>
    </div>
</header>


<div class="container">
    <div class="row profile">
        <div class="col-md-3">
            <div class="profile-sidebar">
                <div class="profile-userpic">
                    <!-- Insérez ici la photo de profil de l'utilisateur -->
                    <img src="<?= "https://static.thenounproject.com/png/363640-200.png"//$user['photo_url'] ?>" class="img-responsive" alt="">
                </div>
                <div class="profile-usertitle">
                    <div class="profile-usertitle-name">
                        <!-- Insérez ici le nom de l'utilisateur -->
                        <?= $user['prenom'] ?> <?= $user['nom'] ?>
                    </div>
                    <div class="profile-usertitle-job">
                        <!-- Insérez ici le poste de l'utilisateur -->
                        Client
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
