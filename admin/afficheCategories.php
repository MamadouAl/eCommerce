<?php
session_start();
$_SESSION['page_avant_login'] = $_SERVER['REQUEST_URI'];
include '../util/users.php';

// Vérifiez si l'utilisateur est connecté est un admin
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
}

if (empty($_SESSION['admin'])) {
    header("Location: ../login.php");
}

$clientID = $_SESSION['clientID'];
$admin = getUserByID($clientID);

$categories = getAllCategories();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0"
            crossorigin="anonymous"></script>
    <title>Admin: Catégories</title>
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
                    <a class="nav-link active" style="font-weight: bold;" aria-current="page" href="../admin/afficheCategories.php">Catégories</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="../admin/ajouteCategorie.php">Nouvelle catégorie</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="supprimeCategorie.php">Suppression</a>
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
    <h1>Liste des Catégories</h1>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Nom de la Catégorie</th>
            <th scope="col">Éditer</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($categories as $categorie): ?>
            <tr>
                <th scope="row"><?= $categorie['categorieid'] ?></th>
                <td><?= $categorie['nom'] ?></td>
                <td><a href="modifCategorie.php?id=<?= $categorie['categorieid'] ?>"><i class="fa fa-pencil" style="font-size: 30px;"></i></a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
