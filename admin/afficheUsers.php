<?php
session_start();
$_SESSION['page_avant_login'] = $_SERVER['REQUEST_URI'];

require('../util/users.php'); // Inclure votre fichier de fonctions pour les utilisateurs

if (empty($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
}

$clientID = $_SESSION['clientID'];
$admin = getUserByID($clientID);
$users = getAllUsers(); // Récupérer la liste de tous les utilisateurs

// Fonction pour mettre à jour le statut d'un utilisateur
if (isset($_POST['update_role'])) {
    $clientID = $_POST['clientID'];
    $nouveauStatut = $_POST['nouveau_role'];
    changeRole($clientID, $nouveauStatut);

    //envoyer un email au client
    $client = getUserByID($clientID);
    $envoyerA = $client ['email'];
    $objet = "ROLE MIS A JOUR";
    $message = "<html lang='fr'>
                    <body>
                        <p>Bonjour ".$client['nom']." ".$client['prenom'].", <br><br>
                        Nous vous informons que votre role a été mis à jour.</p>
                        <p>Le nouveau role de votre compte est: <b>".$nouveauStatut."</b><br>
                        Vous pouvez consulter les détails de votre compte en vous connectant à votre compte sur notre site Web.</p>
                        <p>Cordialement,<br>
                        L'administrateur du site Web
                        </p>
                    </body>
                </html>";

    envoiEmail($client['prenom']." ".$client['nom'], $envoyerA, $objet, $message);
    header("Location: #");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
    <title>Admin: Liste des Clients</title>
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
                    <a class="nav-link" aria-current="page" href="#">Users</a>
                </li>
            </ul>
            <div style="margin-right: 500px">
                <h5 style="color: #545659; opacity: 1.5;">Connecté en tant que: <b style="color: chocolate"><?php echo $admin['nom'] . ' ' . $admin['prenom'] ?></b></h5>
            </div>
            <a class="btn btn-danger d-flex" style="display: flex; justify-content: flex-end;" href="../deconnexion.php">Se déconnecter</a>
        </div>
    </div>
</nav>
<div class="container">
    <h1>Liste des Clients</h1>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Nom</th>
            <th scope="col">Prénom</th>
            <th scope="col">Email</th>
            <th scope="col">Actions</th>
            <th scope="col">Role</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <th scope="row"><?= $user['clientid'] ?></th>
                <td><?= $user['nom'] ?></td>
                <td><?= $user['prenom'] ?></td>
                <td><?= $user['email'] ?></td>
                <td>
                    <a href="gestionCommandes.php?clientID=<?= $user['clientid'] ?>" class="btn btn-info">
                        Gérer les commandes
                    </a>
                </td>
                <td>
                    <!-- si le role est admin, le bouton au rouge sinon le bouton au bleu -->
                    <?php if ($user['role'] == 'admin'): ?>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modal_<?= $user['clientid'] ?>">
                            <?php $user['role'] == 'admin' ? print('Admin') : print('Client') ?>
                        </button>
                    <?php else: ?>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_<?= $user['clientid'] ?>">
                            <?php $user['role'] == 'admin' ? print('Admin') : print('Client') ?>
                        </button>
                    <?php endif; ?>
                </td>
            </tr>
            <!--Donne moi le Modal pour la mise à jour du rôle -->


        <div class="modal fade" id="modal_<?= $user['clientid'] ?>" tabindex="-1" role="dialog"
             aria-labelledby="modalLabel" aria-hidden="true">
            <!--le reste du code pour le modal -->
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="post">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalLabel">Mettre à jour le Role</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="clientID" value="<?= $user['clientid'] ?>">
                            <div class="mb-3">
                                <label for="nouveau_role" class="form-label">Nouveau Role</label>
                                <select class="form-select" name="nouveau_role" id="nouveau_role">
                                    <option value="admin">Admin</option>
                                    <option value="user">Client</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="update_role" class="btn btn-primary">Mettre à jour</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
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
