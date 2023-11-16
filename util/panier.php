<?php
include 'categorie.php';

/**
 *  Fonction pour obtenir le contenu du panier
 */

/**
 *  Cette fonction permet de créer un nouveau panier pour un client donné.
 * @param $clientID
 * @return array
 */
function creePanier($clientID): int|array {
    $query = "INSERT INTO panier (clientID, DateCreation) VALUES ('$clientID', NOW()) RETURNING panierID";
    $result = pg_query(connexion(), $query);
    $panierID=0;
    if ($result) {
        $row = pg_fetch_assoc($result);
        $panierID = $row['panierid'];

    }
    pg_free_result($result);
    pg_close(connexion());
    return $panierID;
}

/**
 *  Cette fonction permet d'ajouter un produit au panier.
 * @param $panierID
 * @param $produitID
 * @param $quantite
 * @return bool|resource
 */
function ajouterAuPanier($clientID, $produitID, $quantite): void {
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
        // Le produit n'y est pas, ajoutez-le
        $query = "INSERT INTO produit_panier (panierID, produitID, quantite) VALUES ((SELECT panierID FROM panier WHERE clientID = $1), $2, $3)";
        pg_prepare(connexion(), "insert_produit_panier", $query);
        pg_execute(connexion(), "insert_produit_panier", array($clientID, $produitID, $quantite));
    }
    pg_free_result($result);
    pg_close(connexion());
}

/**
 *  Cette fonction permet de supprimer un produit du panier.
 * @param $panierID
 * @param $produitID
 * @return bool|resource
 */
function deleteProPanier($panierID, $produitID) {
    $query = "DELETE FROM produit_panier WHERE panierID = '$panierID' AND produitID = '$produitID'";
    $result = pg_query(connexion(), $query);

    pg_free_result($result);
    pg_close(connexion());
    return $result;
}

/**
 *  Cette fonction permet de modifier la quantité d'un produit dans le panier.
 * @param $panierID
 * @param $produitID
 * @param $quantite
 * @return bool|resource
 */
function updateQuantitePanierProduit($panierID, $produitID, $quantite) : void{
    $query = "UPDATE produit_panier SET quantite = '$quantite' WHERE panierID = '$panierID' AND produitID = '$produitID'";
    pg_query(connexion(), $query);
    pg_close(connexion());
}

/**
 *  Cette fonction recupere le contenu du panier d'un client.
 * @param $clientID
 * @return bool|resource
 */
function getUserContenuPanier($clientID): array {
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
    pg_free_result($result);
    pg_close(connexion());
    return $panier;
}

/**
 *  Cette fonction permet d'obtenir l'ID du panier en fonction de l'ID du client.
 * @param $clientID
 * @return bool|resource
 */
function getPanierIDByClientID($clientID) {
    $query = "SELECT panierID FROM panier WHERE clientID = $1";
    pg_prepare(connexion(), "get_panier_by_client", $query);
    $result = pg_execute(connexion(), "get_panier_by_client", array($clientID));

    if ($result && pg_num_rows($result) > 0) {
        $row = pg_fetch_assoc($result);
        $panierID = $row['panierid'];
        pg_free_result($result);
        pg_close(connexion());
        return $panierID;
    } else {
        pg_close(connexion());
        return false; // Le client n'a pas de panier
    }
}

/**
 * Fonctions qui permet de retourner le nombre de produits dans le panier d'un client
 * en donnant son ID
 */

function getNombreProduitPanier($clientID) {
    $query = "SELECT COUNT(*) AS nombre_produits FROM produit_panier WHERE panierID = (SELECT panierID FROM panier WHERE clientID = $1)";
    pg_prepare(connexion(), "get_nombre_produits", $query);
    $result = pg_execute(connexion(), "get_nombre_produits", array($clientID));

    $nombreProduits= 0;
    if ($result) {
        $row = pg_fetch_assoc($result);
        $nombreProduits = $row['nombre_produits'];
    }
        pg_free_result($result);
        pg_close(connexion());
        return $nombreProduits;
}