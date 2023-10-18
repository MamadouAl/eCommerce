<?php
include 'commandes.php';


// Récupération de la liste de tous les produits
function getAllProduits() {
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

// Récupération des détails d'un produit par son ID
function getProduitByID($produitID){
	$query = "SELECT * FROM produit WHERE produitID = '$produitID'";
    $result = pg_query(connexion(), $query);

    $produit = pg_fetch_assoc($result);

    pg_close(connexion());
    return $produit;

}

// Ajout d'un nouveau produit (pour les administrateurs)
function addProduit($nom, $description, $prix, $image_url, $categorieID) {
    $sql = "INSERT INTO produit (nom, description, prix, image_url, categorieID) 
            VALUES ($1, $2, $3, $4, $5) RETURNING produitID";

    pg_prepare(connexion(), "add", $sql);
    $result = pg_execute(connexion(), "add", array($nom, $description, $prix, $image_url, $categorieID));

    $id = null;
    if($result){
        $line = pg_fetch_assoc($result);
        $id = $line['produitid'];
    }
  //  pg_free_result($result); //libere la ressource
    if (is_a($result, 'PgSql\Result')) {
        pg_free_result($result);
    }
    
    pg_close(connexion());
    return $id;
}


// Recherche de produits par nom ou catégorie
function rechercheProduit($motCle) {
    // Recherche de produits par nom ou catégorie
    $query = "SELECT * FROM produit WHERE nom ILIKE '%$motCle%' OR categorieID IN (SELECT categorieID FROM categorie WHERE nom ILIKE '%$motCle%')";
    $result = pg_query(connexion(), $query);

    $produit = array();
    while ($row = pg_fetch_assoc($result)) {
        $produit[] = $row;
    }

    pg_close(connexion());
    return $produit;
}

// Modification d'un produit (pour les administrateurs)
function updateProduit($productID, $nom, $description, $prix, $image_url,  $categorieID,) {
    $query = "UPDATE produit 
              SET nom = $1, description = $2, prix = $3, image_url = $4, categorieID = $5
              WHERE produitID = $6";

    pg_prepare(connexion(), "update", $query);
    $result = pg_execute(connexion(), "update", array($nom, $description, $prix, $image_url,$categorieID, $productID));
}


// Fonction pour supprimer un produit 
function deleteProduit($produitID) {
    $query = "DELETE FROM produit WHERE produitID = '$produitID'";
    $result = pg_query(connexion(), $query);

    return $result;
}
$image = 'https://store.storeimages.cdn-apple.com/4668/as-images.apple.com/is/iphone-15-pro-finish-select-202309-6-7inch-blacktitanium_AV1_GEO_EMEA?wid=5120&hei=2880&fmt=p-jpg&qlt=80&.v=1692845694886';

//print_r(addProduit('Iphone 14', 'Le nouvel iphone 15 sorti sorti en 2023', 100, $image, 1));
$image="./images/iphone15.jpeg";
//updateProduit(1, 'Iphone 14', 'Nouvel iphone 15 sorti sorti en fin 2023', 1850, $image, 1);

?>
