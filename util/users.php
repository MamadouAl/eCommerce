<?php
/**
 * Fichier de fonctions pour les utilisateurs
 */
include 'panier.php';
include './Config/config.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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
function changePassword($userID, $nouveauMotDePasse): void {
    // Hasher le nouveau mot de passe
    $hashedPassword = password_hash($nouveauMotDePasse, PASSWORD_DEFAULT);

    $sql = "UPDATE client SET passwd = $2 WHERE clientID = $1";
    pg_prepare(connexion(), "passwd", $sql);
    pg_execute(connexion(), "passwd", array($userID, $hashedPassword));
    pg_close(connexion());
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

/**
 * Fonction qui sauvegarde le jeton de réinitialisation du mot de passe dans la base de données
 * @param $userID
 * @param $resetToken
 */
function savePasswordResetToken($userID, $resetPass) : void {
    $sql = "UPDATE client SET reset_passwd = $2 WHERE clientID = $1";
    pg_prepare(connexion(), "saveResetToken", $sql);
    pg_execute(connexion(), "saveResetToken", array($userID, $resetPass));
    pg_close(connexion());
}


/**
 * Fonction pour envoyer un e-mail de réinitialisation de mot de passe
 * @param string $nom Nom de l'utilisateur
 * @param string $email Adresse e-mail
 * @param string $resetToken Jeton de réinitialisation de mot de passe
 * @return string Message de confirmation
 */
function sendPasswordResetEmail($nom, $email, $resetToken) {
    require './phpmailer/phpmailer/src/Exception.php';
    require './phpmailer/phpmailer/src/PHPMailer.php';
    require './phpmailer/phpmailer/src/SMTP.php';
    try {
        // Création d'une instance de PHPMailer
        $mail = new PHPMailer(true);

        // Configuration du serveur SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['email'];
        $mail->Password = $_ENV['mot_de_pass'];
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Destinataire et expéditeur
        $mail->setFrom('mamadoual@madal.fr', 'no-reply'); // Exepediteur du mail
        $mail->addAddress($email, $nom); // Destinataire

        // Contenu de l'e-mail
        $message = "<html><body>";
        $message .= "<h3>Bonjour $nom,</h3>";
        $message .= "<p>Vous avez demandé une réinitialisation de mot de passe sur notre site.</p>";
        $message .= "<p>Utilisez le lien ci-dessous pour réinitialiser votre mot de passe :</p>";
        $message .= "<p><a href='http://localhost/perso/eCommerce/resetPasswd2.php?token=$resetToken'>Réinitialiser le mot de passe</a></p>";
        $message .= "<p>Si vous n'avez pas demandé cette réinitialisation, veuillez ignorer cet e-mail.</p>";
        $message .= "<p>Cordialement</p>
                    <p>MAD SHOP</p>";
        $message .= "</body></html>";

        $mail->isHTML(true);
        $mail->Subject = "Réinitialisation de mot de passe";
        $mail->Body = $message;

        // Envoi de l'e-mail
        $mail->send();

        // Message de confirmation
        $confirmationMessage = "Un e-mail de réinitialisation de mot de passe a été envoyé à $email. Veuillez vérifier votre boîte de réception.";
        return $confirmationMessage;
    } catch (Exception $e) {
        return 'Une erreur s\'est produite lors de l\'envoi de l\'e-mail : ' . $mail->ErrorInfo;
    }
}

/**
 * Fonction pour vérifier si le token de réinitialisation du mot de passe est valide
 * @param $resetToken
 * @return id de l'utilisateur
 */
function verifToken($resetToken) {
    $sql = "SELECT * FROM client WHERE reset_passwd = $1";
    pg_prepare(connexion(), "verify", $sql);
    $result = pg_execute(connexion(), "verify", array($resetToken));

    if ($result) {
        $user = pg_fetch_assoc($result);
        if ($user) {
            return $user['clientid'];
        }
    }
    return null;
}
/*
// Utilisation de la fonction
$nom = "Utilisateur"; // Remplacez par le nom de l'utilisateur
$email = "utilisateur@example.com"; // Remplacez par l'adresse e-mail de l'utilisateur
$resetToken = "abcdef123456"; // Remplacez par le véritable jeton de réinitialisation de mot de passe

$confirmation = sendPasswordResetEmail($nom, $email, $resetToken);

// Affichage du message de confirmation
echo $confirmation;
*/