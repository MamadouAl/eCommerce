<?php
session_start();
require("./util/users.php");
$_SESSION['page_avant_login'] = $_SERVER['REQUEST_URI'];
$Produits = getAllProduits();
$id = null;
$nom = null;
$user = null;
$Produits = null;
$nbProd = 0;

if(isset($_SESSION['clientID'])) {
// Récupérer le nom de l'utilisateur connecté
$id = $_SESSION['clientID'];
$user = getUserByID($id);  //le nom de l'utilisateur réel
$nom = $user['prenom'] . ' ' . $user['nom'];
$nbProd = getNombreProduitPanier($_SESSION['clientID']);
}

if (isset($_GET['query'])) {
    $motCle = $_GET['query'];
    $resultats = rechercheProduit($motCle);
    $Produits = $resultats;
}else if(isset($_GET['id'])){
    $categorieID = $_GET['id'];
    $Produits = getProduitsByCategorieID($categorieID);
}
else{
    $Produits = getAllProduits();
}


// Si l'utilisateur est connecté, on affiche du contenu spécifique ici
    $connectee ='
         <div class="col-sm-4 offset-md-1 py-4">
          
             <div class="profile-sidebar">
                <div class="profile-userpic">
                    <!-- la photo de profil de l utilisateur -->
                    <img src="https://static.thenounproject.com/png/363640-200.png" class="img-responsive" style="color: white" alt="user_image" height="60" width="60">
                </div>
                <div class="profile-usertitle">
                    <div class="profile-usertitle-name">
                        <h4 class="text-white">'.$nom.'</h4>
                    </div>
                    <div class="profile-usertitle-job">
                        <b class="text-white">Client</b>
                    </div>
                </div>
                <div class="profile-userbuttons">
                    <a href="monProfil.php" class="btn btn-danger btn-sm">Mon Profil</a>
                </div>
             </div>
             
             
        </div>';

$NonConnectee = '<div class="col-sm-4 offset-md-1 py-4">
      <h4 ><a class="btn btn-danger" href="inscription.php">M\'INSCRIRE</a></h4>
  </div>';


    $content = ' <div class="recherche-bloc">
<div class="col-md-6 offset-md-3 mt-5">
              <form method="GET">
                  <div class="input-group">
                      <input type="text" class="form-control" name="query" placeholder="Rechercher un produit...">
                      <div class="input-group-append">
                          <button class="btn btn-primary" type="submit">Rechercher</button>
                      </div>
                  </div>
              </form>
          </div>
          </div>';
    $content .= "
        <section class='py-5 text-center container'>";
       if(isset($_SESSION['clientID'])) {
           $content .= "'<h1>Bienvenue, $nom!</h1> ";
       }
       $content .= "
        <h2 class='fw-light'>Mad Shop</h2>
            <p>
                <a href='#' class='btn btn-primary my-2'>Main call to action</a>
                <a href='#' class='btn btn-secondary my-2'>Secondary action</a>
            </p>
        </section>";
        if(empty($Produits)){
            $content .=" <h1 style='text-align: center'>Aucun produit trouvé !</h1>
             ";
        }
        $content .= "
        <div class='album py-5 bg-body-tertiary'>
            <div class='container'>
                <div class='row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3'> ";

            foreach ($Produits as $produit) {
                $content .= "
                        <div class='produit-case' xmlns=\"http://www.w3.org/1999/html\">
                            <div class='card shadow-sm'>
                                <h3>{$produit['nom']}</h3>
                                <a href='afficherUnProduit.php?produitid={$produit['produitid']}'>
                                <img id='produit_image' src='{$produit['image_url']}' alt='{$produit['nom']}'> </a>
                                <div class='card-body'>
                                    <p class='card-text'>";
                                $content .= substr($produit['description'], 0, 150);
                                    $content .="...</p><div class='d-flex justify-content-between align-items-center'>
                                        <div class='btn-group'>
                                            <a href='afficherUnProduit.php?produitid={$produit['produitid']}'><button type='button' class='btn btn-sm btn-success'>Voir plus</button></a>
                                        </div>
                                        <small class='text' style='font-weight: bold;'>{$produit['prix']} €</small>
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
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="MamadouAl">
    <meta name="generator" content="Hugo 0.80.0">
    <title>Home</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <!--Fontawesome CDN-->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link rel="stylesheet" href="./CSS/style.css">


</head>
<body>
<header >
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

    <div class="navbar navbar-dark shadow-sm" >
        <div class="container">
            <a href="index.php" class="navbar-brand d-flex align-items-center">
                <strong style="margin-left: 10px;">Mad Shop</strong>
            </a>


            <div class="navbar-brand">
                <ul class="page_menu_nav">
                    <li><a href="index.php" <?php if(!isset($_GET['id']))  echo 'id="ici"';?> href="index.php">Accueil</a></li>
                    <li><a href="produits.php">Produits</a></li>

                    <li class="page_menu_item has-children">
                        <a href="#" <?php if(isset($_GET['id']))  echo 'id="ici"'; ?>
                        >Catégories<i class="fa fa-angle-down"></i></a>
                        <ul class="page_menu_selection">
                            <?php
                            //fonction  pour obtenir la liste des catégories
                            $categories = getAllCategories();
                            // Parcourez les catégories et créez une liste d'éléments
                            foreach ($categories as $categorie) {
                                echo '<li id="lesCategories"><a href="index.php?id=' . $categorie['categorieid'] . '">' . $categorie['nom'] . '</a></li>';
                            }
                            ?>
                        </ul>
                    </li>

                    <li><a href="monPanier.php"><i class="fas fa-shopping-cart fa-3x"></i><strong style="color: red"><?= $nbProd ?></strong></a></li>

                    <?php if (isset($_SESSION['clientID'])) : ?>
                        <li><a href="mesCommandes.php">Commandes</a></li>
                        <li><a href="monProfil.php"><i class="fas fa-user fa-2x"></i></a></li>
                        <li class="deconnexion">
                            <a  href="deconnexion.php" >
                                <img src="./images/deconnexion.png" alt="deconnexion icon" ">
                                </a> </li>
                    <?php else : ?>
                        <li><a class="btn btn-danger" href="login.php">Login</a></li>
                    <?php endif; ?>

                    <li class="page_menu_item has-children">
                        <a href="#"><img src="./images/fr.png" alt="Langue" width="30" height="20"><i class="fa fa-angle-down"></i></a>
                        <ul class="page_menu_selection">
                            <li><a  href="./en/"><img src="./images/en.png" alt="Langue" width="30" height="20"><i class="fa fa-angle-down"></i></a></li>
                        </ul>
                    </li>

                </ul>
            </div>


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
        <a href="#">Back to top</a>
    </div>
</footer>

</body>
</html>
