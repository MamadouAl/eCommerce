<?php
/**
 * Fichier de fonctions pour les utilisateurs
 */
include 'panier.php';

/**
 * Inscription d'un nouveau client dans la base de données
 * @param array $client
 * @return mixed|null
 */
function addClient(array $client) : ?int{
    $connected = connexion();
    $requete = "INSERT INTO client VALUES(DEFAULT, $1, $2, $3, $4, $5) RETURNING clientID";
    pg_prepare($connected, "add", $requete);
    $resu = pg_execute($connected, "add", $client);

    $id = null;
    if ($resu) {
        $row = pg_fetch_assoc($resu);
        $id = $row['clientid'];
    }

    pg_free_result($resu);
    pg_close($connected);
    return $id;
}


/**
 * Connexion d'un utilisateur
 * @param $email
 * @param $motDePasse
 * @return bool
 */
function loginUser($email, $motDePasse) : bool  {
    //Requete de recuperation des informations
    $sql = "SELECT * FROM client WHERE email = $1";
    $result = pg_prepare(connexion(), "log", $sql);

    if (!$result) {
        die("La préparation de la requête a échoué : " . pg_last_error(connexion()));
    }
    $execute = pg_execute(connexion(), "log", array($email));

    if (isset($execute)) {
        $user = pg_fetch_assoc($execute);
        if ($user && $user['passwd'] === $motDePasse) {
            $_SESSION['userID'] = $user['clientid'];
            pg_free_result($execute);
            pg_close(connexion());
            return true;
        }
    }
    
    pg_free_result($execute);
    pg_close(connexion());
    return false; // L'utilisateur n'existe pas
}

/**
 * Récupérer toutes les informations d'un utilisateur
 * @return array
 */
function getAllUsers() : array{
    $query = "SELECT * FROM client";
    $result = pg_query(connexion(), $query);

    $users = array();
    while ($row = pg_fetch_assoc($result)) {
        $users[] = $row;
    }

    pg_close(connexion());
    return $users;
}

/**
 * Récupérer les informations d'un utilisateur par son ID
 * @param $userID
 * @return array
 */
function getUserByID($userID) : array {
    $sql = "SELECT * FROM client WHERE clientID = $1";
    pg_prepare(connexion(), "userId", $sql);
	$resu = pg_execute(connexion(), "userId", array($userID));
	
	$users = array();

	if(($resu)){
		$user = pg_fetch_assoc($resu);
        if ($user) {
            $users = $user;
        }
	}
	pg_free_result($resu);
    pg_close(connexion());
    return $users;
}

/**
 * Récupérer les informations d'un utilisateur par son email
 * @param $email
 * @return array
 */
function getUserByEmail($email) : array {
	$connected = connexion();

    //Requete de recuperation des informations
    $sql = "SELECT * FROM client WHERE email = $1";
    pg_prepare($connected, "userId", $sql);
	$resu = pg_execute($connected, "userId", array($email));
	
	$users = array();

	if(($resu)){
		$users = pg_fetch_assoc($resu);
	}
	pg_free_result($resu);
    pg_close($connected);
    return $users;
}

/**
 * Fonction qui permet de mettre à jour les informations d'un utilisateur
 * @param $userID
 * @param $nom
 * @param $prenom
 * @param $email
 * @param $adresseLivraison
 * @return bool
 */
function updateUser($userID, $nom, $prenom, $email, $adresseLivraison) : bool
{
	$connected = connexion();

	$sql = "UPDATE client SET nom=$2, prenom =$3, email=$4, adresseLivraison=$5 WHERE clientID=$1";

	pg_prepare($connected, "update", $sql);
	$result = pg_execute($connected, "update", array($userID, $nom, $prenom, $email, $adresseLivraison));
    $bool = false;
    if (!$result) {
        die("La mise à jour a échoué : " . pg_last_error($connected));
} else {
        $bool = true;
    }


    pg_free_result($result);
    pg_close($connected);
    return $bool;
}

/**
 * Fonction qui permet de mettre à jour le mot de passe d'un utilisateur
 * @param $userID
 * @param $nouveauMotDePasse
 */
function changePassword($userID, $nouveauMotDePasse) : void {
	$sql = "UPDATE client SET passwd =$2 WHERE clientID=$1";
	pg_prepare(connexion(), "passwd", $sql);
	pg_execute(connexion(), "passwd", array($userID, $nouveauMotDePasse));
}

/**
 * Fonction qui permet de verifier si un email existe déjà dans la base de données
 * @param $email
 * @return bool qui indique si l'email existe déjà ou non
 */
function emailExisteDeja($email) : bool {
    $query = "SELECT COUNT(*) FROM client WHERE email = $1";
    pg_prepare(connexion(), "checkEmail", $query);
    $result = pg_execute(connexion(), "checkEmail", array($email));
    
    if ($result) {
        $row = pg_fetch_assoc($result);
        // Si le compte des e-mails est supérieur à zéro, cela signifie que l'e-mail existe déjà
        return intval($row["count"]) > 0;
    }
    return false;
}


/**
 * Fonction qui permet de supprimer un utilisateur
 * @param $userID
 */
function deleteUser($userID) : void {
    $query = "DELETE FROM client WHERE clientID = $1";
    pg_prepare(connexion(), "delete", $query);
    pg_execute(connexion(), "delete", array($userID));
}

//print_r(getUserByID(1));
//print_r(getUserByEmail('barry@gmail.com'));