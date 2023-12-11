<?php
session_start();
$_SESSION['page_avant_login'] = $_SERVER['REQUEST_URI'];
include '../util/users.php';

if (empty($_SESSION['role']) || $_SESSION['role'] !== 'admin')
{
    header("Location: ../login.php");
}


$clientID =$_SESSION['clientID'];
$admin = getUserByID($clientID);

$produits = getAllProduits();


?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0"
            crossorigin="anonymous"></script>
    <title>Tous les produits</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
          integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous"
          referrerpolicy="no-referrer" />
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
                    <a class="nav-link active" style="font-weight: bold;" aria-current="page" href="../admin/afficheProduits.php">Produits</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="../admin/ajouterProduit.php">Nouveau</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="supprimeProduits.php">Suppression</a>
                </li>
            </ul>
            <div style="margin-right: 500px">
                <h5 style="color: #545659; opacity: 1.5;">Connecté en tant que: <b style="color: chocolate"><?php echo $admin['nom'].' '.$admin['prenom'] ?></b></h5>
            </div>
            <a class="btn btn-danger d-flex" style="display: flex; justify-content: flex-end;" href="../deconnexion.php">Se deconnecter</a>
        </div>
    </div>
</nav>
<div class="album py-5 bg-light">
    <div class="container">
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Image</th>
                    <th scope="col">Nom</th>
                    <th scope="col">Prix</th>
                    <th scope="col">Description</th>
                    <th scope="col">Catégorie</th>
                    <th scope="col">Editer</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($produits as $produit): ?>
                    <?php $id =$produit['categorieid'];
                        $categorie =getCategorieByID($id);

                    ?>
                    <tr>
                        <th scope="row"><?= $produit['produitid'] ?></th>
                        <td>
                            <img src="<?php //si la chaine commence par http ou https, on ne fait rien
                            if (preg_match('/^https?:\/\//', $produit['image_url'])) {
                                echo $produit['image_url'];
                            } else {
                                echo "." . $produit['image_url'];
                            }

                             ?>" alt="<?= $produit['nom'] ?>" style="width: 25%">
                        </td>
                        <td><?= $produit['nom'] ?></td>
                        <td style="font-weight: bold; color: green;"><?= $produit['prix'] ?>€</td>
                        <td><?php print(substr($produit['description'], 0, 100)); ?>...</td>
                        <td><?= $categorie['nom'] ?></td>
                        <td><a href="modifProduits.php?id=<?= $produit['produitid'] ?>"><i class="fa fa-pencil" style="font-size: 30px;"></i></a></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
