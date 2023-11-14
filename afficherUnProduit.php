<?php
session_start();
$_SESSION['page_avant_login'] = $_SERVER['REQUEST_URI'];

require('./util/users.php');

$id = null;
$nom = null;
$user = null;
$clientID = null;

if(isset($_SESSION['clientID'])) {
// Récupérer le nom de l'utilisateur connecté
    $id = $_SESSION['clientID'];
    $user = getUserByID($id);  //le nom de l'utilisateur réel
    $nom = $user['prenom'] . ' ' . $user['nom'];
    $clientID = $_SESSION['clientID'];
}



if (!isset($_GET['produitid'])) {
    header("Location: index.php");
    exit;
}

    $produitID = $_GET['produitid'];
    $produit = getProduitByID($produitID);

    if (!$produit) {
        header("Location: index.php"); // Rediriger vers la page d'accueil si le produit n'existe pas
        exit;
    }


// Si l'utilisateur est connecté, on affiche du contenu spécifique ici
$connectee ='
         <div class="col-sm-4 offset-md-1 py-4">
          <h4 class="text-white">'.$nom.'</h4> 
          <ul class="list-unstyled">
            <li><a href="deconnexion.php" class="text-white">Se deconnecter</a></li>
            <li><a href="monProfil.php" class="text-white">Mon Profil</a></li>
          </ul>
        </div>';

$NonConnectee = '<div class="col-sm-4 offset-md-1 py-4">
    <h4 class="text-white">Sign in_up</h4>
    <ul class="list-unstyled">
      <li><a href="login.php" class="text-white">Se connecter</a></li>
      <li><a href="inscription.php" class="text-white">M\'inscrire</a></li>
    </ul>
  </div>';

?>

<!doctype html>
<html lang="fr" data-bs-theme="auto">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="MamadouAl">
    <meta name="generator" content="Hugo 0.80.0">
    <link rel="icon" type="image/x-icon" href="<?= $produit['image_url']?>">
    <title><?=$produit['nom']?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>

    <style>
        #iciProduit {
            color: white;
            border-bottom: solid;
        }
    </style>
  </head>
  <body>
<header>
  <div class="collapse" id="navbarHeader" >
      <div class="container">
          <!-- fait moi un bloc où afficher la langue -->
          <div class="language-flag">
              <img src="./images/fr.png" alt="Langue" width="30" height="20">
          </div>

          <div class="row">
              <div class="col-sm-8 col-md-7 py-4">
                  <h4 class="text-white" style="color: white">Apropos</h4>
                  <p class="text-muted">
                      Mad Shop, votre destination incontournable pour des achats en ligne exceptionnels.
                      Découvrez une vaste sélection de produits de qualité, des dernières tendances en mode aux gadgets high-tech innovants.
                      Notre engagement envers la qualité et la satisfaction du client fait de Mad Shop le choix ultime pour vos besoins d'achat en ligne.
                      Explorez notre boutique et laissez-vous emporter par la folie du shopping en ligne.
                  </p>
              </div>

              <?php if(isset($_SESSION['clientID'])) echo $connectee;
              else echo $NonConnectee; ?>
          </div>
      </div>
  </div>

    <?= include('./includes/monHeader.php'); ?>
  </header>



  <main>
  <div class="album py-5 bg-body-tertiary">
    <div class="container">
      <div class="row">
        <div class="col">
          <div class="card shadow-sm">
            <h3><?= $produit['nom'] ?></h3>
            <img src="<?= $produit['image_url'] ?>" class="bd-placeholder-img-lg" alt="<?= $produit['nom'] ?>" style="width: 55%">
            <div class="card-body">
              <p class="card-text"><?= $produit['description'] ?></p>
              <p>Prix: <?= $produit['prix'] ?> €</p>

              <form method="post" >
                <input type="hidden" name="produitID" value="<?= $produitID ?>">
                <label for="quantite">Quantité :</label>
                <input type="number" name="quantite" id="quantite" value="1" min="1">
                <button type="submit" name="ajouter_panier" class="btn btn-primary bd-mode-toggle" >Ajouter au Panier</button>
              </form>
                  <?php
                  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                      if (isset($_SESSION['clientID'])) {
                          $quantite = $_POST['quantite'];
                          ajouterAuPanier($clientID, $produitID, $quantite);
                          echo "<h5 style='color: green'> Produit ajouté </h5>";
                      } else {
                          echo "<h5 style='color: red'> Veuillez vous connecter pour ajouter au panier </h5>";
                          exit;
                          //header("Location: login.php");


                      }
                  }
                  ?>

              <a href="index.php" class="btn btn-secondary">Retour à la liste des produits</a>
                <a href="monPanier.php">

                    <img src="./images/icon_panier.png" class="bd-placeholder-img" title="Panier" alt="monPanier" width="60" height="50">
                </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>


<footer class="text-body-secondary py-5">
  <div class="container">
    <p class="float-end mb-1">
      <a href="#">Back to top</a>
    </p>
    <p class="mb-1">Album example is &copy; Bootstrap, but please download and customize it for yourself!</p>
    <p class="mb-0">New to Bootstrap? <a href="/">Visit the homepage</a> or read our <a href="/docs/5.3/getting-started/introduction/">getting started guide</a>.</p>
  </div>
</footer>


</body>
</html>
