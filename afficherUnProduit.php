<?php
session_start();
$_SESSION['page_avant_login'] = $_SERVER['REQUEST_URI'];

require('./util/panier.php');

if(isset($_SESSION['clientID']))
$clientID = $_SESSION['clientID'];


if (!isset($_GET['produitid'])) {
    header("Location: index.php"); // Rediriger vers la page d'accueil si aucun produit n'est sélectionné
    exit;
}

    $produitID = $_GET['produitid'];
    $produit = getProduitByID($produitID);

    if (!$produit) {
        header("Location: index.php"); // Rediriger vers la page d'accueil si le produit n'existe pas
        exit;
    }
    
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
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }
        
        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }

        .bd-mode-toggle {
            z-index: 1500;
        }
        
        .bd-mode-toggle .dropdown-menu .active .bi {
            display: block !important;
        }
    </style>
  </head>
  <body>
    
  <header>
  <div class="collapse bg-dark" id="navbarHeader">
    <div class="container">
      <div class="row">
        <div class="col-sm-8 col-md-7 py-4">
          <h4 class="text-white">About</h4>
          <p class="text-muted">Add some information about the album below, the author, or any other background context. Make it a few sentences long so folks can pick up some informative tidbits. Then, link them off to some social networking sites or contact information.</p>
        </div>
        <div class="col-sm-4 offset-md-1 py-4">
          <h4 class="text-white">Sign in</h4>
          <ul class="list-unstyled">
            <li><a href="login.php" class="text-white">Se connecter</a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <div class="navbar navbar-dark bg-dark shadow-sm">
    <div class="container">
      <a href="index.php" class="navbar-brand d-flex align-items-center">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" aria-hidden="true" class="me-2" viewBox="0 0 24 24"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
        <strong>Mad Shop</strong>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
    </div>
  </div>
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
                  <?php
                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            if (!isset($_SESSION['clientID'])) {
                                header("Location: login.php"); // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
                                exit;
                            }
                            $quantite =$_POST['quantite'];
                            ajouterAuPanier($clientID, $produitID, $quantite);
                            echo "<h5 style='color: green'> Produit ajouté </h5>";

                        }


                  ?>
              </form>
              <a href="index.php" class="btn btn-secondary">Retour à la liste des produits</a>
                <a href="monPanier.php">

                    <img src="./images/icon_panier.png" class="bd-placeholder-img" alt="monPanier" width="60" height="50">
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
