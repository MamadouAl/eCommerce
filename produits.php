<?php
session_start();
require("./util/users.php");
$_SESSION['page_avant_login'] = $_SERVER['REQUEST_URI'];

$Produits = null;

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


if(empty($Produits)){
    $content =" <h1 style='text-align: center'>Aucun produit trouvé !</h1>
             ";
}
$content = "
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


                               $content .= "...</p>  
                                      <div class='d-flex justify-content-between align-items-center'>
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
        <title>Produits</title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">

        <!--Fontawesome CDN-->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
        <link rel="stylesheet" href="./CSS/style.css">

        <style>
            #iciProduit {
                color: white;
                border-bottom: solid;
            }
        </style>
    </head>

    <body>
        <header>
            <?php require('./includes/monHeader.php'); ?>
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
