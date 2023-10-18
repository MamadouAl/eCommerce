<!--  Gestion des utilisateurs :  -->

<?php
include 'produit.php';

// Inscription d'un nouveau client
	function addClient(array $client) {
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


// Authentification d'un utilisateur
function loginUser($email, $motDePasse) {
    $connected = connexion(); 
    
    //Requete de recuperation des informations
    $sql = "SELECT * FROM client WHERE email = $1";
    
    // Préparez la requête et vérifiez si la préparation a réussi.
    $result = pg_prepare($connected, "log", $sql);
    if (!$result) {
        die("La préparation de la requête a échoué : " . pg_last_error($connected));
    }

    // Exécutez la requête en passant le paramètre $email.
    $execute = pg_execute($connected, "log", array($email));

    if ($execute) {
        $user = pg_fetch_assoc($execute); // Récupération des résultats sous forme de tableau associatif.
        if ($user && $user['passwd'] === $motDePasse) {
            $_SESSION['userID'] = $user['clientid']; // Assurez-vous des noms de colonnes corrects.
            pg_free_result($execute);
            pg_close($connected);
            return true;
        }
    }
    
    pg_free_result($execute);
    pg_close($connected);

    // Authentification échouée
    return false;
}

function getAllUsers() {
    $query = "SELECT * FROM client";
    $result = pg_query(connexion(), $query);

    $users = array();
    while ($row = pg_fetch_assoc($result)) {
        $users[] = $row;
    }

    pg_close(connexion());
    return $users;
}


// Récupération des informations d'un utilisateur par son ID
function getUserByID($userID){
	$connected = connexion(); 
    
    //Requete de recuperation des informations
    $sql = "SELECT * FROM client WHERE clientID = $1";
    pg_prepare($connected, "userId", $sql);
	$resu = pg_execute($connected, "userId", array($userID));
	
	$users = array();

	if(isset($resu)){
		$users = pg_fetch_assoc($resu);
	}
	pg_free_result($resu);
    pg_close($connected);

    return $users;
}

// Récupération des informations d'un utilisateur par son email

function getUserByEmail($email){
	$connected = connexion(); 
    
    //Requete de recuperation des informations
    $sql = "SELECT * FROM client WHERE email = $1";
    pg_prepare($connected, "userId", $sql);
	$resu = pg_execute($connected, "userId", array($email));
	
	$users = array();

	if(isset($users)){
		$users = pg_fetch_assoc($resu);
	}
	pg_free_result($resu);
    pg_close($connected);

    return $users;
}

// Mise à jour des informations d'un utilisateur
function updateUser($userID, $nom, $prenom, $email, $adresseLivraison) : void {
	$connected = connexion();
	
	$sql = "UPDATE client SET nom=$2, prenom =$3, email=$4, adresseLivraison=$5 WHERE clientID=$1";
	
	pg_prepare($connected, "update", $sql);
	$resu = pg_execute($connected, "update", array($userID, $nom, $prenom, $email, $adresseLivraison));
}

// Modification du mot de passe d'un utilisateur
function changePassword($userID, $nouveauMotDePasse) {
	$connected = connexion();
	
	$sql = "UPDATE client SET passwd =$2 WHERE clientID=$1";
	
	pg_prepare($connected, "passwd", $sql);
	$resu = pg_execute($connected, "passwd", array($userID, $nouveauMotDePasse));
}

// Déconnexion de l'utilisateur
function logoutUser() {
	// Démarre ou restaure la session
    session_start();
    
    // Détruit toutes les données de session
    session_destroy();
    
    // Redirige l'utilisateur vers une page de déconnexion ou d'accueil
    header("Location: index.php");
    exit;
}

// admin.php

function authentifierUser($email, $password) {
    // Vérifiez les informations d'identification dans la base de données
    $query = "SELECT clientID, email, passwd FROM client WHERE email = $1";
    pg_prepare(connexion(), "authentifier_client", $query);
    $result = pg_execute(connexion(), "authentifier_client", array($email));

    if ($result && pg_num_rows($result) > 0) {
        $row = pg_fetch_assoc($result);
        $hashedPassword = $row['passwd'];

        // Vérifiez si le mot de passe correspond
        if (password_verify($password, $hashedPassword)) {
            // Authentification réussie, retournez l'ID du client
            return $row['clientID'];
        }
    }

    // Authentification échouée, retournez null
    return null;
}

function emailExisteDeja($email) {
    // Préparez la requête pour vérifier si l'e-mail existe déjà
    $query = "SELECT COUNT(*) FROM client WHERE email = $1";
    pg_prepare(connexion(), "check_email", $query);
    
    // Exécutez la requête avec l'e-mail en tant que paramètre
    $result = pg_execute(connexion(), "check_email", array($email));
    
    if ($result) {
        // Récupérez le résultat sous forme de tableau associatif
        $row = pg_fetch_assoc($result);
        
        // Si le compte des e-mails est supérieur à zéro, cela signifie que l'e-mail existe déjà
        return intval($row["count"]) > 0;
    } else {
        // Gestion des erreurs de requête (par exemple, erreur de connexion à la base de données)
        return false;
    }
}

//print_r(getUserByEmail('mad@gmail.com'));

?>
