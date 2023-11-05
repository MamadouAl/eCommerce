<?php
session_start();
$_SESSION['page_avant_login'] = $_SERVER['REQUEST_URI'];


include '../util/users.php';

if(!isset($_SESSION['admin']) OR empty($_SESSION['admin'])) //admin
{
    header("Location: ../login.php");
}

$clientID =$_SESSION['clientID'];
$admin = getUserByID($clientID);

if(!isset($_GET['id']) OR !is_numeric($_GET['id'])){
    header("Location: afficheProduits.php");
}

$id = $_GET['id'];
$produit = getProduitByID($id);
$categorieID= $produit['categorieid'];
$categories = getAllCategories();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        
    $nom = $_POST['nom'];
    $description = ($_POST['desc']);
    $prix = (float)$_POST['prix']; // Conversion en float
    $image_url = $_POST['image'];
    $categorieID = (int)$_POST['categorieID']; 

    $produitID = $_GET['id'];

    try 
    {
        updateProduit($produitID, $nom, "$description", $prix, $image_url, $categorieID);
        header('Location: afficheProduits.php');
    } 
    catch (Exception $e) 
    {
        echo $e->getMessage();
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
    <title>Modifier le produit</title>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="admin.php" >Administration</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
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
                    <a class="nav-link" href="supprimeProduits.php">Suppression</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" style="font-weight: bold; color: green" href="modifProduits.php" >Modification</a>
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
   
    <form method="post">
        <div class="mb-3">
            <label for="categories">Selectionnez une categorie</label>
            <select id="categorieID" name="categorieID">
            <?php
                        foreach ($categories as $categorie): ?>
                            <option value="<?= $categorie['categorieid'] ?>"><?= $categorie['nom'] ?></option>
             <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="nom" class="form-label">Nom du produit</label>
            <input type="text" class="form-control" name="nom" value="<?= $produit['nom'] ?>"  required/>        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" name="desc" required><?= $produit['description'] ?></textarea>
        </div>
        <div class="mb-3">
            <label for="prix" class="form-label">Prix</label>
            <input type="number" class="form-control" name="prix" value="<?= $produit['prix'] ?>" required/>        </div>
        <div class="mb-3">
            <label for="image" class="form-label">L'image du produit</label>
            <input type="name" class="form-control" name="image" value="<?= $produit['image_url'] ?>" required/>
        </div>
        <button type="submit" name="valider" class="btn btn-success">Enregistrer</button>
        
        <button type="button" class="btn btn-success" style="background :red;" onclick="annulerFormulaire()">Annuler</button>

    </form>

    <script>
        function annulerFormulaire() {
            // Rediriger vers une autre page, par exemple "index.php"
            window.location.href = "afficheProduits.php";
        }
    </script>

        </div>
    </div>
</div>
</body>
</html>
