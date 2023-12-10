<?php
session_start();
include '../util/users.php';

// Assurez-vous que l'utilisateur est connecté en tant qu'administrateur
if (empty($_SESSION['role']) || $_SESSION['role'] !== 'admin'){
    header("Location: login.php");
    exit;
}

if (!isset($_GET['clientID'])) {

    echo "L'clientID n'est pas spécifié.";
}
// Récupérez les commandes spécifiques à cet utilisateur
$clientID = $_GET['clientID'];

// Fonction pour mettre à jour le statut d'une commande
if (isset($_POST['update_status'])) {
    $commandeID = $_POST['commandeID'];
    $nouveauStatut = $_POST['nouveau_statut'];
    updateCommandeStatut($commandeID, $nouveauStatut);
}

$commandes = getCommandesClient($clientID);
$users = getUserByID($clientID);


// Récupérez les commandes depuis la base de données
//$commandes = getAllCommandes();
//print_r($commandes);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
    <title>Gestion des Commandes</title>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="admin.php">Administration</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="afficheUsers.php">Gestion des Commandes</a>
                </li>
                <!-- Ajoutez d'autres liens de navigation pour d\'autres fonctionnalités d\'administration si nécessaire -->


            </ul>
            <a class="btn btn-danger" href="../deconnexion.php">Se déconnecter</a>
        </div>
    </div>
</nav>
<div class="container">
    <h1>Commandes de <i style="color: chocolate"><?=$users['prenom'].' '.$users['nom'] ?></i></h1>
    <table class="table">
        <thead>
        <tr>
            <th>#</th>
            <!--<th>Client</th> -->
            <th>Date de Commande</th>
            <th>Statut</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($commandes as $commande): ?>
            <tr>
                <td><?= $commande['commandeid'] ?></td>
                <!--<td><?= $commande['clientid'] ?></td> -->
                <td><?= $commande['datecommande'] ?></td>
                <td><?= $commande['statut'] ?></td>
                <td>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_<?= $commande['commandeid'] ?>">
                        Modifier Statut
                    </button>
                    <a href="detailsCommande.php?commandeID=<?= $commande['commandeid'] ?>" class="btn btn-info">
                        Voir les Détails
                    </a>
                </td>
            </tr>
            <!-- Modal pour la mise à jour du statut -->
            <div class="modal fade" id="modal_<?= $commande['commandeid'] ?>" tabindex="-1" role="dialog"
                 aria-labelledby="modalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="post">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalLabel">Mettre à jour le Statut</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="commandeID" value="<?= $commande['commandeid'] ?>">
                                <div class="mb-3">
                                    <label for="nouveau_statut" class="form-label">Nouveau Statut</label>
                                    <select class="form-select" name="nouveau_statut" id="nouveau_statut">
                                        <option value="En cours de traitement">En cours de traitement</option>
                                        <option value="Expédiée">Expédiée</option>
                                        <option value="Livraison en attente">Livraison en attente</option>
                                        <option value="Annulée">Annulée</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                <button type="submit" name="update_status" class="btn btn-primary">Enregistrer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
';
}