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
 * @return bool
 */
function deleteProduit($produitID): bool {
    // Vérifier si le produit existe dans la table produit
    $query = "SELECT count(*) FROM produit WHERE produitID = $1";
    pg_prepare(connexion(), "check", $query);
    $result = pg_execute(connexion(), "check", array($produitID));

    if ($result !== false) {
        $count = pg_fetch_row($result)[0];
        pg_free_result($result);

        if ($count > 0) {
            // Supprimer le produit des commandes dans la table produit_commande
            $query = "DELETE FROM produit_commande WHERE produitID = $1";
            pg_prepare(connexion(), "delete1", $query);
            pg_execute(connexion(), "delete1", array($produitID));

            // Supprimer le produit de la table produit
            $query = "DELETE FROM produit WHERE produitID = $1";
            pg_prepare(connexion(), "delete2", $query);
            $result = pg_execute(connexion(), "delete2", array($produitID));

            if ($result !== false) {
                pg_free_result($result);
                pg_close(connexion());
                return true;
            } else {
                // La suppression du produit de la table produit a échoué
                // Gérer l'erreur ici si nécessaire
                return false;
            }
        } else {
            // Le produit avec cet ID n'existe pas
            // Gérer le cas où l'ID n'est pas valide
            return false;
        }
    } else {
        // La requête de vérification a échoué
        // Gérer l'erreur ici si nécessaire
        return false;
    }
}




//fonction qui ajoute une image de produit
function addImage($produitID, $image_url) : void {
    $query = "UPDATE produit SET image_url = '$image_url' WHERE produitID = '$produitID'";
    pg_query(connexion(), $query);
    pg_close(connexion());
}

//fonction qui retourne le nombre de produits
function countProduits() : int {
    $query = "SELECT COUNT(*) FROM produit";
    $result = pg_query(connexion(), $query);
    $count = pg_fetch_row($result)[0];
    pg_free_result($result);
    pg_close(connexion());
    return $count;
}
