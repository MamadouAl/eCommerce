<?php 
include 'connexion.php';

//Fonction pour passer une nouvelle commande
function passerCommande($clientID, $produits) {
    // Insérer une nouvelle commande dans la table "commande"
    $dateCommande = date("Y-m-d"); // Date de la commande actuelle
    $statut = 'En cours de traitement'; // Vous pouvez définir un statut par défaut
    $query = "INSERT INTO commande (clientID, dateCommande, statut) VALUES ($1, $2, $3) RETURNING commandeID";
    pg_prepare(connexion(), "insert_commande", $query);
    $result = pg_execute(connexion(), "insert_commande", array($clientID, $dateCommande, $statut));
    
    // Récupérer l'ID de la nouvelle commande
    $row = pg_fetch_assoc($result);
    $commandeID = $row['commandeid'];

    // Insérer les produits inclus dans la commande dans la table "produit_commande"
    foreach ($produits as $produit) {
        $produitID = $produit['produitid'];
        $quantite = $produit['quantite'];
        $query = "INSERT INTO produit_commande (commandeID, produitID, quantite) VALUES ($1, $2, $3)";
        pg_prepare(connexion(), "insert_produit_commande", $query);
        pg_execute(connexion(), "insert_produit_commande", array($commandeID, $produitID, $quantite));
    }
    return $commandeID;
}


//Fonction pour récupérer la liste des commandes d'un client :
function getCommandesClient($clientID) : array {
    // Récupérez l'historique des commandes d'un utilisateur
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


//    Récupération des détails d'une commande donnée :
function getCommandeDetails($commandeID) :array {
    // Récupérez les détails d'une commande spécifique
    $query = "SELECT * FROM commande WHERE commandeID = '$commandeID'";
    $result = pg_query(connexion(), $query);

    $commande = pg_fetch_assoc($result);
    pg_close(connexion());

    return $commande;
}

//    Mise à jour de l'état d'une commande (administrateurs) :
function updateCommandeStatut($commandeID, $statut) {
    // Mettez à jour l'état de la commande
    $query = "UPDATE commande SET statut = '$statut' WHERE commandeID = '$commandeID'";
    $result = pg_query(connexion(), $query);

    pg_close(connexion());
    return $result;
}

function marquerCommandeExpediee($commandeID) {
    $statut = 'Expédiée';
    $query = "UPDATE commande SET statut = $1 WHERE commandeID = $2";
    pg_prepare(connexion(), "update_statut_commande", $query);
    pg_execute(connexion(), "update_statut_commande", array($statut, $commandeID));
}

function annulerCommande($commandeID) {
    $statut = 'Annulée';
    $query = "UPDATE commande SET statut = $1 WHERE commandeID = $2";
    pg_prepare(connexion(), "update_statut_commande", $query);
    pg_execute(connexion(), "update_statut_commande", array($statut, $commandeID));
}

//Fonction pour récupérer toutes les commandes en attente ou expédiées
function getCommandesEnAttenteOuExpediees() {
    $query = "SELECT * FROM commande WHERE statut IN ('En cours de traitement', 'Expédiée')";
    $result = pg_query(connexion(), $query);

    $commandes = array();
    while ($row = pg_fetch_assoc($result)) {
        $commandes[] = $row;
    }

    return $commandes;
}


