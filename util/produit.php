<?php
include 'commandes.php';
/**
 * Fichier de fonctions pour les produits
 */



/**
 * Cette fonction permet d'obtenir tous les produits.
 * @return array
 */
function getAllProduits() : array {
	$sql = "SELECT * FROM produit ORDER BY produitID DESC";
	pg_prepare(connexion(), "all", $sql);
	$result = pg_execute(connexion(), "all", array());
	
	$res=array();
	if(isset($result)){
		$res = pg_fetch_all($result);
	}
	pg_free_result($result);
	pg_close(connexion());
	return $res;
}

/**
 * Cette fonction permet d'obtenir les détails d'un produit par son ID.
 * @param $produitID
 * @return array
 */
function getProduitByID($produitID) : array{
	$query = "SELECT * FROM produit WHERE produitID = '$produitID'";
    $result = pg_query(connexion(), $query);

    $produit = array();
    if($result && pg_num_rows($result) > 0) {
        $produit = pg_fetch_assoc($result);
    }
    pg_free_result($result);
    pg_close(connexion());
    return $produit;

}

/**
 * Cette fonction permet d'ajouter un nouveau produit.
 * @param $nom
 * @param $description
 * @param $prix
 * @param $image_url
 * @param $categorieID
 * @return mixed|null
 */
function addProduit($nom, $description, $prix, $image_url, $categorieID) : ?int {
    $sql = "INSERT INTO produit (nom, description, prix, image_url, categorieID) 
            VALUES ($1, $2, $3, $4, $5) RETURNING produitID";

    pg_prepare(connexion(), "add", $sql);
    $result = pg_execute(connexion(), "add", array($nom, $description, $prix, $image_url, $categorieID));

    $id = null;
    if($result){
        $line = pg_fetch_assoc($result);
        $id = $line['produitid'];
    }
    if (is_a($result, 'PgSql\Result')) {
        pg_free_result($result);
    }
    pg_free_result($result);
    pg_close(connexion());
    return $id;
}


/*
 * Cette fonction permet de rechercher un produit par un mot clé ou sa categorie.
 * @param $motCle
 * @return array
 */
 function rechercheProduit($motCle) : array {
    $query = "SELECT * FROM produit WHERE nom ILIKE '%$motCle%' OR categorieID IN (SELECT categorieID FROM categorie WHERE nom ILIKE '%$motCle%')";
    $result = pg_query(connexion(), $query);

    $produit = array();
    while ($row = pg_fetch_assoc($result)) {
        $produit[] = $row;
    }
    pg_free_result($result);
    pg_close(connexion());
    return $produit;
}

/**
 * Cette fonction permet de modifier un produit.
 * @param $productID
 * @param $nom
 * @param $description
 * @param $prix
 * @param $image_url
 * @param $categorieID
 */
function updateProduit($productID, $nom, $description, $prix, $image_url,  $categorieID,): void {
    $query = "UPDATE produit 
              SET nom = $1, description = $2, prix = $3, image_url = $4, categorieID = $5
              WHERE produitID = $6";

    pg_prepare(connexion(), "update", $query);
   pg_execute(connexion(), "update", array($nom, $description, $prix, $image_url,$categorieID, $productID));
    pg_close(connexion());
}

/**
 * Cette fonction permet de supprimer un produit.
 * @param $produitID
 * @return bool|resource
 */
function deleteProduit($produitID) : void {
    $query = "DELETE FROM produit WHERE produitID = '$produitID'";
    pg_query(connexion(), $query);
    pg_close(connexion());
}
