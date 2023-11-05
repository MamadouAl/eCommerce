<?php 
include 'connexion.php';
/**
 * Fonctions de gestion des commandes
 */

/**
 * Fonction pour passer une nouvelle commande
 * @param $clientID
 * @param $panierID
 * @return mixed
 */
function passerCommande($clientID, $panierID) {
    // Insérer une nouvelle commande dans la table "commande"
    $dateCommande = date("Y-m-d"); // Date de la commande actuelle
    $statut = 'En cours de traitement'; // Statut par défaut
    $query = "INSERT INTO commande (clientID, dateCommande, statut) VALUES ($1, $2, $3) RETURNING commandeID";
    $result = pg_query_params(connexion(), $query, array($clientID, $dateCommande, $statut));

    // Récupérer l'ID de la nouvelle commande
    $row = pg_fetch_assoc($result);
    $commandeID = $row['commandeid'];

    // Récupérer les produits inclus dans le panier associé à l'utilisateur
    $queryProduitsPanier = "SELECT produitID, quantite FROM produit_panier WHERE panierID = $1";

    $resultProduitsPanier = pg_query_params(connexion(), $queryProduitsPanier, array($panierID));

    // Insérer les produits du panier dans la table "produit_commande"
    while ($rowProduit = pg_fetch_assoc($resultProduitsPanier)) {
        $produitID = $rowProduit['produitid'];
        $quantite = $rowProduit['quantite'];
        $query = "INSERT INTO produit_commande (commandeID, produitID, quantite) VALUES ($1, $2, $3)";

        pg_query_params(connexion(), $query, array($commandeID, $produitID, $quantite));
    }

    // Supprimer les produits du panier car ils ont été inclus dans la commande
    $querySupprimerProduitsPanier = "DELETE FROM produit_panier WHERE panierID = $1";
    pg_query_params(connexion(), $querySupprimerProduitsPanier, array($panierID));

    return $commandeID;
}

/**
 * Récupérez l'historique des commandes d'un utilisateur
 * @return array
 */
function getCommandesClient($clientID) : array {
    $query = "SELECT * FROM commande WHERE clientID = '$clientID' ORDER BY dateCommande DESC";
    $result = pg_query(connexion(), $query);

    $commande = array();
    while ($row = pg_fetch_assoc($result)) {
        $commande[] = $row;
    }
    pg_free_result($result);
    pg_close(connexion());
    return $commande;
}

/**
 * Récupérez les détails d'une commande spécifique (produits inclus)
 * @param $commandeID
 * @return array
 */
function getCommandeDetails($commandeID): array {
    $query = "SELECT commande.commandeid, commande.datecommande, commande.statut, commande.clientid,
                     produit.nom, produit.description, produit.prix
              FROM commande
              JOIN produit_commande ON commande.commandeid = produit_commande.commandeid
              JOIN produit ON produit_commande.produitid = produit.produitid
              WHERE commande.commandeid = $1";

    $result = pg_query_params(connexion(), $query, array($commandeID));

    // Initialisez les détails de la commande.
    $commandeDetails = array(
        'commandeid' => null,
        'datecommande' => null,
        'statut' => null,
        'clientid' => null,
        'produits' => array()
    );

    while ($row = pg_fetch_assoc($result)) {
        // Remplissez les détails de la commande.
        $commandeDetails['commandeid'] = $row['commandeid'];
        $commandeDetails['datecommande'] = $row['datecommande'];
        $commandeDetails['statut'] = $row['statut'];
        $commandeDetails['clientid'] = $row['clientid'];

        // Ajoutez les produits inclus dans cette commande.
        $commandeDetails['produits'][] = array(
            'nom' => $row['nom'],
            'description' => $row['description'],
            'prix' => $row['prix']
        );
    }

    pg_free_result($result);
    pg_close(connexion());
    return $commandeDetails;
}

/**
 * Mettre à jour l'état d'une commande spécifique (En cours de traitement, Expédiée, Annulée)
 * @param $commandeID
 * @return array
 */
function updateCommandeStatut($commandeID, $statut) : void {
    $query = "UPDATE commande SET statut = '$statut' WHERE commandeID = '$commandeID'";
    $result = pg_query(connexion(), $query);

    pg_free_result($result);
    pg_close(connexion());
}

/**
 * Annule une commande spécifique
 * @param $commandeID
 * @return array
 */

function annulerCommande($commandeID) : void {
    $statut = 'Annulée';
    $query = "UPDATE commande SET statut = $1 WHERE commandeID = $2";
    pg_prepare(connexion(), "update_statut", $query);
    pg_execute(connexion(), "update_statut", array($statut, $commandeID));

    pg_close(connexion());
}

/**
 * Récupérez toutes les commandes en attente ou expédiées
 * @return array
 */
function getCommandesEnAttenteOuExpediees() {
    $query = "SELECT * FROM commande WHERE statut IN ('En cours de traitement', 'Expédiée')";
    $result = pg_query(connexion(), $query);

    $commandes = array();
    while ($row = pg_fetch_assoc($result)) {
        $commandes[] = $row;
    }
    pg_free_result($result);
    pg_close(connexion());
    return $commandes;
}

/**
 * Récupérez l'historique des commandes d'un utilisateur avec les produits inclus
 * @return array
 */
function getHistoriqueCommandesAvecProduits($userID): array {
    $historique = [];

    $query = "SELECT * FROM commande WHERE clientID = $userID";
    $result = pg_query(connexion(), $query);

    while ($commande = pg_fetch_assoc($result)) {
        $commandeID = $commande['commandeid'];

        $queryProduits = "SELECT produit.produitID, produit.nom, produit.description, produit.prix, produit.image_url
                          FROM produit_commande
                          JOIN produit ON produit_commande.produitID = produit.produitID
                          WHERE produit_commande.commandeID = $commandeID";
        $resultProduits = pg_query(connexion(), $queryProduits);

        $commandeDetails = [
            'commandeid' => $commandeID,
            'datecommande' => $commande['datecommande'],
            'statut' => $commande['statut'],
            'produits' => []
        ];

        while ($produit = pg_fetch_assoc($resultProduits)) {
            $commandeDetails['produits'][] = [
                'produitid' => $produit['produitid'],
                'nom' => $produit['nom'],
                'description' => $produit['description'],
                'prix' => $produit['prix'],
                'image_url' => $produit['image_url']
            ];
        }
        $historique[] = $commandeDetails;
    }
    pg_free_result($result);
    pg_close(connexion());
    return $historique;
}
