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

$ajout ="";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_SESSION['clientID'])) {
        $quantite = $_POST['quantite'];
        //verifier si le panier existe
        $panierID = getPanierIDByClientID($clientID);
        if (!$panierID || $panierID == 0) {
            $panierID = creePanier($clientID);
        }
        ajouterAuPanier($clientID, $produitID, $quantite);
        //header("Location: #");
        $ajout = "<h5 style='color: green'> Produit ajouté </h5>";
    } else {
        $ajout = "<h5 style='color: red'> Veuillez vous connecter pour ajouter au panier </h5>";

    }
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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <!--Fontawesome CDN-->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">

    <link rel="stylesheet" href="./CSS/header.css">
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

    <?php include('./includes/monHeader.php'); ?>
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

              <form method="post" action="#" >
                <input type="hidden" name="produitID" value="<?= $produitID ?>">
                <label for="quantite">Quantité :</label>
                <input type="number" name="quantite" id="quantite" value="1" min="1">
                <button type="submit" name="ajouter_panier" class="btn btn-primary bd-mode-toggle" >Ajouter au Panier</button>
              </form>
                <br>
                <?php
                echo $ajout;
                ?>

                <a href="index.php" class="btn btn-secondary">Retour à la liste des produits</a>
                <a href="monPanier.php">

                    <a href="monPanier.php"><i class="fas fa-shopping-cart fa-3x"></i><strong style="color: red"><?php if(isset($_SESSION['clientID'])) echo getNombreProduitPanier($_SESSION['clientID']); ?></strong></a>
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
      <a href="#">Back to top</a>
   </div>
</footer>


</body>
</html>
