<?php
session_start();
$_SESSION['page_avant_login'] = $_SERVER['REQUEST_URI'];

require('../util/users.php'); // Inclure votre fichier de fonctions pour les catégories

if (empty($_SESSION['role']) || $_SESSION['role'] !== 'admin'){
    header("Location: ../login.php");
}

$clientID = $_SESSION['clientID'];
$admin = getUserByID($clientID);
$categorieID = $_GET['id'];
$categorie = getCategorieByID($categorieID);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    try {
        updateCategorie($categorieID, $nom);
        echo "Modification de la catégorie effectuée !";
        // Redirection vers la page souhaitée
        header('Location: afficheCategories.php'); // Assurez-vous que le nom du fichier est correct
        exit;
    } catch (Exception $e) {
        echo "Problème : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0"
            crossorigin="anonymous"></script>
    <title><?= $categorie['nom'] ?></title>
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
                    <a class="nav-link" aria-current="page" href="../admin/ajouteCategorie.php">Nouvelle Catégorie</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="supprimeCategorie.php">Suppression</a>
                </li>
            </ul>
            <div style="margin-right: 500px">
                <h5 style="color: #545659; opacity: 1.5;">Connecté en tant que: <b style="color: chocolate"><?php echo $admin['nom'].' '.$admin['prenom'] ?></b></h5>
            </div>
            <a class="btn btn-danger d-flex" style="display: flex; justify-content: flex-end;" href="../deconnexion.php">Se deconnecter</a>
        </div>
    </div>
</nav>
<div class="container">
    <h1>Modifier une catégorie</h1>
    <form method="post">
        <div class="mb-3">
            <label for="nom" class="form-label">Nouveau nom de la catégorie</label>
            <input type="text" class="form-control" name="nom" value="<?= $categorie['nom'] ?>" required>
        </div>
        <button type="submit" name="valider" class="btn btn-primary">Modifier la catégorie</button>
    </form>
</div>
</body>
</html>

