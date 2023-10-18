<?php


include 'categorie.php';

//    Création d'un panier :
function creePanier($clientID) {

    // Créez un nouveau panier pour un client donné
    $query = "INSERT INTO panier (clientID, DateCreation) VALUES ('$clientID', NOW()) RETURNING panierID";
    $result = pg_query(connexion(), $query);
    $panierID=0;
    if ($result) {
        $row = pg_fetch_assoc($result);
        $panierID = $row['panierid'];

    }

    pg_close(connexion());
    return $panierID;
}

//    Ajout de produits au panier :
//Cette fonction permet d'ajouter un produit spécifique avec une quantité donnée au panier.
function ajouterAuPanier($clientID, $produitID, $quantite) {
    // Vérifier si le produit est déjà dans le panier du client
    $query = "SELECT * FROM produit_panier WHERE panierID = (SELECT panierID FROM panier WHERE clientID = $1) AND produitID = $2";
    pg_prepare(connexion(), "check_produit_panier", $query);
    $result = pg_execute(connexion(), "check_produit_panier", array($clientID, $produitID));

    if (pg_num_rows($result) > 0) {
        // Le produit est déjà dans le panier, mettez à jour la quantité
        $query = "UPDATE produit_panier SET quantite = quantite + $3 WHERE panierID = (SELECT panierID FROM panier WHERE clientID = $1) AND produitID = $2";
        pg_prepare(connexion(), "update_produit_panier", $query);
        pg_execute(connexion(), "update_produit_panier", array($clientID, $produitID, $quantite));
    } else {
        // Le produit n'est pas encore dans le panier, ajoutez-le
        $query = "INSERT INTO produit_panier (panierID, produitID, quantite) VALUES ((SELECT panierID FROM panier WHERE clientID = $1), $2, $3)";
        pg_prepare(connexion(), "insert_produit_panier", $query);
        pg_execute(connexion(), "insert_produit_panier", array($clientID, $produitID, $quantite));
    }
}





//    Récupération du contenu du panier :
//Cette fonction permet de récupérer le contenu du panier, y compris les produits inclus et leurs quantités.
/*
function getContenuPanier($panierID) {

    // Récupérez le contenu du panier pour afficher les produits inclus
    $query = "SELECT produit.produitID, produit.nom, produit.description, produit.prix, produit_panier.quantite
              FROM produit_panier
              JOIN produit ON produit_panier.produitID = produit.produitID
              WHERE produit_panier.panierID = '$panierID'";
    $result = pg_query(connexion(), $query);

    $contenuPanier = array();

    while ($row = pg_fetch_assoc($result)) {
        $contenuPanier[] = $row;
    }

    pg_close(connexion());

    return $contenuPanier;
}
*/

//    Suppression de produits du panier :
//Cette fonction permet de supprimer un produit spécifique du panier en fonction de l'ID du panier et de l'ID du produit.
function deleteProPanier($panierID, $produitID) {

    // Supprimez un produit spécifique du panier
    $query = "DELETE FROM produit_panier WHERE panierID = '$panierID' AND produitID = '$produitID'";
    $result = pg_query(connexion(), $query);

    pg_close(connexion());

    return $result;
}

/********************************************/

// Modification de la quantité d'un produit dans le panier
function updateQuantitePanier($panierID, $quantite) {

    // Mettez à jour la quantité du produit dans le panier
    $query = "UPDATE produit_panier SET quantite = '$quantite' WHERE panierID = '$panierID'";
    $result = pg_query(connexion(), $query);

    pg_close(connexion());
    return $result;
}


//Autrement
function getUserContenuPanier($clientID) {
    $query = "SELECT P.*, quantite, panierID 
              FROM produit P
              JOIN produit_panier PP ON P.produitID = PP.produitID 
              WHERE panierID = (SELECT panierID FROM panier WHERE clientID = $1)";
    pg_prepare(connexion(), "contenu", $query);
    $result = pg_execute(connexion(), "contenu", array($clientID));

    $panier = array();
    while ($row = pg_fetch_assoc($result)) {
        $panier[] = $row;
    }

    return $panier;
}

/* //Erreur à corriger !!
// Affichage du contenu du panier
function getUserContenuPanier($userID) {
    // Récupérez le contenu du panier de l'utilisateur
    $query = "SELECT P.nom, P.description, P.prix, PP.panierID, PP.quantite
              FROM produit_panier as PP
              JOIN produit P ON PP.produitID = P.produitID
              WHERE PP.clientID = '$userID'"; // Utilisez "clientID" au lieu de "userID"
    $result = pg_query(connexion(), $query);

    $contenuPanier = array();

    while ($row = pg_fetch_assoc($result)) {
        $contenuPanier[] = $row;
    }

    pg_close(connexion());

    return $contenuPanier;
}
*/

// Fonction pour obtenir l'ID du panier en fonction de l'ID du client
function getPanierIDByClientID($clientID) {
    $connected = connexion();

    $query = "SELECT panierID FROM panier WHERE clientID = $1";
    pg_prepare($connected, "get_panier_by_client", $query);
    $result = pg_execute($connected, "get_panier_by_client", array($clientID));

    if ($result && pg_num_rows($result) > 0) {
        $row = pg_fetch_assoc($result);
        $panierID = $row['panierid'];
        pg_free_result($result);
        pg_close($connected);
        return $panierID;
    } else {
        pg_close($connected);
        return false; // Aucun panier associé à ce client
    }
}


/* Exemple */


//creePanier(1);
//ajoutProdPanier(1,10,3);
//print_r(getUserContenuPanier(1));

?>
