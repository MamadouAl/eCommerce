
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


                    <li><a href="monPanier.php"><i class="fas fa-shopping-cart fa-2x"></i><strong style="color: red"><?php if(isset($_SESSION['clientID'])) echo getNombreProduitPanier($_SESSION['clientID']); ?></strong></a></li>


                    <?php if (isset($_SESSION['clientID'])) : ?>
                        <li><a id="iciCommande" href="mesCommandes.php">Commandes</a></li>
                        <li><a id="iciProfil" href="monProfil.php"><i class="fas fa-user fa-2x"></i></a></li>
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

            <?php if($_SESSION['clientID'] && isset($_SESSION['nom']) && isset($_SESSION['prenom']) !== null) {
                echo '<div >         
                <h6 style="color: chocolate; display: inline;"><i class="fas fa-user"></i> '.$_SESSION['nom'].' '.$_SESSION['prenom'].'</h6>
        </div>';
            }
            ?>
        </div>
    </div>