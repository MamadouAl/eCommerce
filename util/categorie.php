<?php
include 'produit.php';



function addCategorie($nom) {
    $sql = "INSERT INTO categorie VALUES (DEFAULT, '$nom') RETURNING categorieID";
    $result = pg_query(connexion(), $sql);

    $id = null;
    if($result){
        $line = pg_fetch_assoc($result);
        $id = $line['categorieid'];
    }
    pg_free_result($result); //libere la ressource
    pg_close(connexion());
    return $id;
}

function getAllCategories(): array {
    $sql = "SELECT * FROM categorie";
    $result = pg_query(connexion(), $sql);

    $categories = array();
    while ($row = pg_fetch_assoc($result)) {
        $categories[] = $row;
    }

    pg_close(connexion());
    return $categories;
}

function getCategorieByID($categorieID): bool|array {

    $sql = "SELECT * FROM categorie WHERE categorieID = '$categorieID'";
    $result = pg_query(connexion(), $sql);

    $categorie = array();
    if(isset($result)) {
        $categorie = pg_fetch_assoc($result);
    }
    pg_free_result($result);
    pg_close(connexion());

    return $categorie;
}


//    Mettre à jour les détails d'une catégorie 
function updateCategorie($categorieID, $nom): void {
    $sql = "UPDATE categorie SET nom = '$nom' WHERE categorieID = '$categorieID'";
    $result = pg_query(connexion(), $sql);
    pg_close(connexion());
}

//    Supprimer une catégorie par son ID 
function deleteCategorie($categorieID) {
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
    pg_close(connexion());

    return $result;
}

?>
