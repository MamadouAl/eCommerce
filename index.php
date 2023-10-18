<?php
session_start();
require("./util/users.php");
$Produits = getAllProduits();


$id=0;
$nom="";
if (isset($_GET['clientID'])) {
$id = $_GET['clientID'];
$user = getUserByID($id);  //le nom de l'utilisateur réel
$nom = $user['prenom'].' '.$user['nom'];
}

// Vérifiez si l'utilisateur est connecté
if(isset($_SESSION['clientID'])) {
        // Si l'utilisateur est connecté, on affiche du contenu spécifique ici

    $connectee ='
         <div class="col-sm-4 offset-md-1 py-4">
          <h4 class="text-white">'.$nom.'</h4>
          <ul class="list-unstyled">
            <li><a href="deconnexion.php" class="text-white">Se deconnecter</a></li>
          </ul>
        </div>';

    $content = "
        <section class='py-5 text-center container'>
            <!-- Le contenu spécifique pour l'utilisateur connecté... -->
            <h1>Bienvenue, $nom!</h1> </hr>
        </section>";

        $content = "
        <section class='py-5 text-center container'>
        <h1>Bienvenue, $nom!</h1>
        <h1 class='fw-light'>Mad Shop</h1>
            <p class='lead text-body-secondary'>
                Mad Shop, votre destination incontournable pour des achats en ligne exceptionnels. 
                Découvrez une vaste sélection de produits de qualité, des dernières tendances en mode aux gadgets high-tech innovants. 
                Notre engagement envers la qualité et la satisfaction du client fait de Mad Shop le choix ultime pour vos besoins d'achat en ligne. 
                Explorez notre boutique et laissez-vous emporter par la folie du shopping en ligne.
            </p>
            <p>
                <a href='#' class='btn btn-primary my-2'>Main call to action</a>
                <a href='#' class='btn btn-secondary my-2'>Secondary action</a>
            </p>
        </section>
        <div class='album py-5 bg-body-tertiary'>
            <div class='container'>
                <div class='row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3'>
                    <!--ICI -->
                    ";
    foreach ($Produits as $produit) {
        $content .= "
                        <div class='col'>
                            <div class='card shadow-sm'>
                                <h3>{$produit['nom']}</h3>
                                <img src='{$produit['image_url']}' style='width: 85%'>
                                <div class='card-body'>
                                    <p class='card-text'>{$produit['description']}</p>
                                    <div class='d-flex justify-content-between align-items-center'>
                                        <div class='btn-group'>
                                            <a href='affiche_prod.php?produitid={$produit['produitid']}'><button type='button' class='btn btn-sm btn-success'>Voir plus</button></a>
                                        </div>
                                        <small class='text' style='font-weight: bold;'>{$produit['prix']} €</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    ";
    }
    $content .= "
                </div>
            </div>
        </div>
    </div>
    ";
} else {
    // L'utilisateur n'est pas connecté, affichez le contenu standard
    
    $NonConnectee = '<div class="col-sm-4 offset-md-1 py-4">
    <h4 class="text-white">Sign in_up</h4>
    <ul class="list-unstyled">
      <li><a href="login.php" class="text-white">Se connecter</a></li>
      <li><a href="inscription.php" class="text-white">M\'inscrire</a></li>
    </ul>
  </div>';

    $content = "
        <section class='py-5 text-center container'>
            <!-- Contenu standard pour les utilisateurs non connectés... -->
            <h1 class='fw-light'>Mad Shop</h1>
            <p class='lead text-body-secondary'>
                Mad Shop, votre destination incontournable pour des achats en ligne exceptionnels. 
                Découvrez une vaste sélection de produits de qualité, des dernières tendances en mode aux gadgets high-tech innovants. 
                Notre engagement envers la qualité et la satisfaction du client fait de Mad Shop le choix ultime pour vos besoins d'achat en ligne. 
                Explorez notre boutique et laissez-vous emporter par la folie du shopping en ligne.
            </p>
            <p>
                <a href='#' class='btn btn-primary my-2'>Main call to action</a>
                <a href='#' class='btn btn-secondary my-2'>Secondary action</a>
            </p>
        </section>
        <div class='album py-5 bg-body-tertiary'>
            <div class='container'>
                <div class='row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3'>
                    <!--ICI -->
                    ";
    foreach ($Produits as $produit) {
        $content .= "
                        <div class='col'>
                            <div class='card shadow-sm'>
                                <h3>{$produit['nom']}</h3>
                                <img src='{$produit['image_url']}' style='width: 85%'>
                                <div class='card-body'>
                                    <p class='card-text'>{$produit['description']}</p>
                                    <div class='d-flex justify-content-between align-items-center'>
                                        <div class='btn-group'>
                                            <a href='affiche_prod.php?produitid={$produit['produitid']}'><button type='button' class='btn btn-sm btn-success'>Voir plus</button></a>
                                        </div>
                                        <small class='text' style='font-weight: bold;'>{$produit['prix']} €</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    ";
    }
    $content .= "
                </div>
            </div>
        </div>
    </div>
    ";
}

?>
<!DOCTYPE html>
<html lang="fr" data-bs-theme="auto">
<head>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="MamadouAl">
    <meta name="generator" content="Hugo 0.80.0">
    <title>Home</title>

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
<?php if(isset($_SESSION['clientID'])) echo $connectee;
        else echo $NonConnectee; ?>
<!--
        <div class="col-sm-4 offset-md-1 py-4">
          <h4 class="text-white">Sign in</h4>
          <ul class="list-unstyled">
            <li><a href="login.php" class="text-white">Se connecter</a></li>
          </ul>
        </div>
    -->

      </div>
    </div>
  </div>
  <div class="navbar navbar-dark bg-dark shadow-sm">
    <div class="container">
      <a href="#" class="navbar-brand d-flex align-items-center">
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
    <?php echo $content; ?>
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
