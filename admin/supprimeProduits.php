<?php
session_start();
$_SESSION['page_avant_login'] = $_SERVER['REQUEST_URI'];

include '../util/users.php';

if (empty($_SESSION['role']) || $_SESSION['role'] !== 'admin'){
    header("Location: ../login.php");
}
$clientID =$_SESSION['clientID'];
$admin = getUserByID($clientID);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['valider']) && isset($_POST['idproduit'])) {
        $idproduit = htmlspecialchars(strip_tags($_POST['idproduit']));
        if (!empty($idproduit) && is_numeric($idproduit)) {
            try {
                $result = deleteProduit($idproduit);
                if ($result) {
                    // La suppression a réussi
                    header("Location: supprimeProduits.php"); // Redirection vers la page de suppression
                } else {
                    // La suppression a échoué
                    echo "La suppression du produit a échoué.";
                }
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        }
    }
}

$Produits = getAllProduits();


?>

<!DOCTYPE html>
<html>
<head>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
    <title>Supprimer un produit</title>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="admin.php">Administration</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="afficheProduits.php">Produits</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="ajouterProduit.php">Nouveau</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" style="font-weight: bold;" href="supprimeProduits.php">Suppression</a>
                </li>
            </ul>

            <div style="margin-right: 500px">
                <h5 style="color: #545659; opacity: 1.5;">Connecté en tant que: <b style="color: chocolate"><?php echo $admin['nom'].' '.$admin['prenom'] ?></b></h5>
            </div>

            <a class="btn btn-danger d-flex" style="display: flex; justify-content: flex-end;" href="../deconnexion.php">Se déconnecter</a>
        </div>
    </div>
</nav>

<div class="album py-5 bg-light">
    <div class="container">
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
            <form method="post">
                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">Identifiant du produit</label>
                    <input type="number" class="form-control" name="idproduit" required>
                </div>
                <button type="submit" name="valider" class="btn btn-primary">Supprimer le produit</button>
            </form>
        </div>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
            <?php foreach ($Produits as $produit): ?>
                <div class="col">
                    <div class="card shadow-sm">
                        <img src="<?= $produit['image_url'] ?>"/>
                        <h3><?= $produit['produitid'] ?></h3>
                        <div class="card-body">
                            <!-- Ajoutez ici les détails du produit -->
                            <p>Nom: <?= $produit['nom'] ?></p>
                            <p>Description: <?= $produit['description'] ?></p>
                            <p>Prix: <?= $produit['prix'] ?> €</p>
                            <!-- Fin des détails du produit -->
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

</body>
</html>
