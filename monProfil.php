<?php
function getUserByID($userID){
    $connected = connexion();

    //Requete de recuperation des informations
    $sql = "SELECT * FROM client WHERE clientID = $1";
    pg_prepare($connected, "userId", $sql);
    $resu = pg_execute($connected, "userId", array($userID));

    $users = array();

    if(isset($users)){
        $users = pg_fetch_assoc($resu);
    }
    pg_free_result($resu);
    pg_close($connected);

    return $users;
}

session_start();

include './util/panier.php';  // Inclure les fonctions pour le panier

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
<html>

<head>
    <title>Profil Utilisateur</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Votre CSS personnalisé va ici */
    </style>
</head>

<body>
<div class="container">
    <div class="row profile">
        <div class="col-md-3">
            <div class="profile-sidebar">
                <div class="profile-userpic">
                    <!-- Insérez ici la photo de profil de l'utilisateur -->
                    <img src="<?= "#"//$user['photo_url'] ?>" class="img-responsive" alt="">
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
                            <a href="#">
                                <i class="glyphicon glyphicon-home"></i>
                                Overview
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="glyphicon glyphicon-user"></i>
                                Account Settings
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="glyphicon glyphicon-ok"></i>
                                Orders
                            </a>
                        </li>
                        <li>
                            <a href="voir_panier.php?panierID=<?= $panierID ?>">
                                <i class="glyphicon glyphicon-flag"></i>
                                Shopping Cart
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
                    <?php foreach ($commandes as $commande) : ?>
                        <li>Commande #<?= $commande['commandeID'] ?> - Date: <?= $commande['dateCommande'] ?></li>
                    <?php endforeach; ?>
                </ul>

                <a href="modifier_profil.php" class="btn btn-primary">Modifier le Profil</a>
            </div>
        </div>
    </div>
</div>
</body>

</html>
