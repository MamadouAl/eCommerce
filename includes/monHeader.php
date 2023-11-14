<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Votre Site E-commerce</title>
    <!-- Ajoutez ici vos balises meta, liens CSS, scripts JS, etc. -->
    <!--  <link rel="stylesheet" href="styles.css">  Lien vers votre fichier CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" id="bootstrap-css">

    <style>
        body {
            font-size: 16px;
        }

        .container {
            width: 80%;
            margin: 0 auto;
        }
        .language-flag {
            margin-left: 97%;
        }

        .language-flag img {
            border: 1px solid #ccc; /* Bordure autour du drapeau, optionnel */
            border-radius: 50%; /* Pour donner une forme de cercle à l'image */
            cursor: pointer; /* Changement de curseur au survol, optionnel */
            transition: transform 0.3s ease; /* Transition en douceur pour un effet au survol */
        }

        .language-flag img:hover {
            transform: scale(1.1); /* Effet d'agrandissement au survol */
        }


        .btn-danger {
            color: #fff;
            background-color: #dc3545;
            border-color: #dc3545;
            border-radius:20px
        }

        #navbarHeader {
            background-image: url('http://getwallpapers.com/wallpaper/full/a/5/d/544750.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            height: 100%;
            font-family: 'Numans', sans-serif;
        }

        .navbar {
            /* background-image: url('http://getwallpapers.com/wallpaper/full/a/5/d/544750.jpg'); */
            background-color: #1c2a42;
            background-size: cover;
            background-repeat: no-repeat;
            height: 100%;
            font-family: 'Numans', sans-serif;
            padding: 15px;
        }

        .page_menu_nav {
            list-style: none;
            display: flex;
            margin: 0;
            padding: 0;
        }

        .page_menu_nav li {
            margin-right: 15px;
        }
        .page_menu_nav a{
            text-decoration: none;
            color: white;
            text-transform: uppercase;
            font-size: initial;

        }

        .page_menu_nav a:hover{
            text-decoration: none;
            color: #ffff;
            border-radius: 20px;
            background-color: #545659;
            padding: 10px;
            border-bottom: solid;
        }

        #ici {
            color: white;
            border-bottom: solid;
        }
        .page_menu_item.has-children:hover .page_menu_selection {
            display: block;
        }

        .page_menu_selection {
            display: none;
            position: absolute;
            /*background-image: url('http://getwallpapers.com/wallpaper/full/a/5/d/544750.jpg'); */
            background-color: #333333;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            z-index: 1;
            border-radius: 20px;
            padding: 10px;
        }

        .page_menu_selection li {
            display: block;
        }

        #lesCategories a {
            text-decoration: none;
            margin-bottom: 5px;
            padding: 0;
            list-style-type: none;
        }

        /* contenu de la page */
        .deconnexion img {
            width: 30px;
            height: 30px;
        }

        .produit-case h3 {
            text-align: center;
            font-size: 100%;
        }
        .produit-case p {
            text-align: center;
            font-size: 100%;
        }



        /* les écrans de taille moyenne, tels que les tablettes */
        @media (max-width: 992px) {
            body {
                font-size: 14px;
            }
            .container {
                width: 90%;
            }
            .page_menu_nav {
                list-style: none;
                display: flex;
                flex-direction: column;
                margin: 0;
                padding: 0;
            }

            .page_menu_nav li {
                margin-right: 10px;
            }
        }

        /* les écrans plus petits, tels que les téléphones */
        @media (max-width: 768px) {
            body {
                font-size: 12px;
            }

            .container {
                width: 100%;
            }
            .page_menu_nav {
                flex-direction: column;
                align-items: center;
            }

            .page_menu_nav li {
                margin-right: 0;
                margin-bottom: 10px;
            }

            .page_menu_selection {
                width: auto;
                height: auto;
                display: none;
                position: absolute;
                /*background-image: url('http://getwallpapers.com/wallpaper/full/a/5/d/544750.jpg'); */
                background-color: #333333;
                box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
                z-index: 1;
                border-radius: 16px;
                font-size: 0.5em;

            }

        }

        @media (min-width: 300px) {
            .produit-case p, h3 {
                font-size: 0.8em;
            }
        }


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
    </style>
</head>
<body>
<header>
    <div class="navbar navbar-dark shadow-sm" >
        <div class="container">
            <a href="index.php" class="navbar-brand d-flex align-items-center">
                <strong style="margin-left: 15px;">Mad Shop</strong>
            </a>

            <div class="navbar-brand">
                <ul class="page_menu_nav">
                    <li><a  href="./index.php">Accueil</a></li>
                    <li><a id="iciProduit" href="produits.php">Produits</a></li>

                    <li class="page_menu_item has-children">
                        <a href="#">Catégories<i class="fa fa-angle-down"></i></a>
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


                    <li><a id="iciPanier" href="monPanier.php">Panier</a></li>


                    <?php if (isset($_SESSION['clientID'])) : ?>
                        <li><a id="iciCommande" href="mesCommandes.php">Commandes</a></li>
                        <li><a id="iciProfil" href="monProfil.php">Mon profil</a></li>
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
            <?php if(isset($_GET['produitid']))
                echo '
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>';
            ?>
        </div>
    </div>
</header>
