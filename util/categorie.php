<?php
include 'produit.php';


/**
 * Fonctions de gestion des catégories
 */

/**
 * Ajouter une nouvelle catégorie
 * @param string $nom
 * @return int|null
 */
function addCategorie(string $nom): ?int {
    $sql = "INSERT INTO categorie VALUES (DEFAULT, '$nom') RETURNING categorieID";
    $result = pg_query(connexion(), $sql);

    $id = null;
    if($result){
        $line = pg_fetch_assoc($result);
        $id = $line['categorieid'];
    }
    pg_free_result($result);
    pg_close(connexion());
    return $id;
}

/**
 * Récupérer toutes les catégories
 * @return array
 */
function getAllCategories(): array {
    $sql = "SELECT * FROM categorie ORDER BY nom";
    $result = pg_query(connexion(), $sql);

    $categories = array();
    while ($row = pg_fetch_assoc($result)) {
        $categories[] = $row;
    }

    pg_close(connexion());
    return $categories;
}

/**
 * Récupérer une catégorie par son ID
 * @param int $categorieID
 * @return bool|array
 */
function getCategorieByID(int $categorieID): bool|array {

    $sql = "SELECT * FROM categorie WHERE categorieID = '$categorieID'";
    $result = pg_query(connexion(), $sql);

    $categorie = array();
    if($result && pg_num_rows($result) > 0) {
        $categorie = pg_fetch_assoc($result);
    }
    pg_free_result($result);
    pg_close(connexion());
    return $categorie;
}


/**
* Met à jour les détails d'une catégorie
* @param int $categorieID
* @param string $nom
*/
function updateCategorie(int $categorieID, string $nom): void {
    $sql = "UPDATE categorie SET nom = '$nom' WHERE categorieID = '$categorieID'";
    $result = pg_query(connexion(), $sql);
    pg_free_result($result);
    pg_close(connexion());
}

/**
 * Suppression  d'une catégorie
 * @param int $categorieID
 * @return bool|resource
 */
function deleteCategorie(int $categorieID) {
    // transaction pour gérer les dépendances de manière atomique
    pg_query(connexion(), "BEGIN");

    // Vérifie s'il y a des produits associés à cette catégorie
    $query = "SELECT produitID FROM produit WHERE categorieID = '$categorieID'";
    $result = pg_query(connexion(), $query);

    if (pg_num_rows($result) > 0) {
        // Il y a des produits liés à cette catégorie, supprimez-les d'abord
        while ($row = pg_fetch_assoc($result)) {
            $produitID = $row['produitid'];
            // Supprimez le produit (vous devrez également gérer les dépendances des produits)
            deleteProduit($produitID);
        }
    } 

    // Supprimez la catégorie maintenant que les produits sont supprimés
    $query = "DELETE FROM categorie WHERE categorieID = '$categorieID'";
    $result = pg_query(connexion(), $query);

    if ($result) {
        // Validez la transaction si tout s'est bien passé
        pg_query(connexion(), "COMMIT");
    } else {
        // Annulez la transaction en cas d'erreur
        pg_query(connexion(), "ROLLBACK");
    }
    // Fermez la connexion
    pg_free_result($result);
    pg_close(connexion());
    return $result;
}

/**
 * Récupérer tous les produits d'une catégorie par son ID
 * @param int $categorieID
 * @return array
 */
function getProduitsByCategorieID($id) {
    $sql = "SELECT * FROM produit WHERE categorieID = '$id' ORDER BY nom";
    $result = pg_query(connexion(), $sql);

    $produits = array();
    while ($row = pg_fetch_assoc($result)) {
        $produits[] = $row;
    }

    pg_close(connexion());
    return $produits;
}
