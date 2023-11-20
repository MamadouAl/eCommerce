<?php
session_start();

require('./util/users.php');
$modif = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    // Vérifier si l'e-mail existe dans la base de données
    if (emailExisteDeja($email)) {
        // Génération d'un token unique pour la réinitialisation du mot de passe
        $token = bin2hex(random_bytes(32));

        // Enregistrement du token dans la base de données
        $user = getUserByEmail($email);
        $userID = $user['clientid'];
        savePasswordResetToken($userID, $token);

        // Envoi de l'e-mail de réinitialisation avec le lien contenant le token
        sendPasswordResetEmail($user['nom'], $email, $token);
        $modif = true;
    } else {
        echo "Cette adresse e-mail n'est pas enregistrée.";
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Mot de passe oublié</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
          integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO"
          crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"
          integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU"
          crossorigin="anonymous">

    <link rel="stylesheet" href="./CSS/login.css">

</head>
<body>
<div class="container">
    <div class="d-flex justify-content-center h-100">
        <div class="card">
            <div class="card-header">
                <h3>Mot de passe oublié</h3>
            </div>
            <div class="card-body">
                <form method="post">
                    <div class="card-img-top">
                        <h3>MAD SHOP</h3>
                        <a href="index.php">
                            <img src="./images/MAD-logo.png" alt="logo site web" width="50%" height="50%">
                        </a>
                    </div>

                    <div class="input-group form-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        </div>
                        <input type="email" name="email" class="form-control" placeholder="Votre e-mail" required>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block">Réinitialiser le mot de passe</button>
                    </div>
                    <?php if ($modif)  {
                     echo '  <div class="alert alert-success" role="alert">
                            Un e-mail de réinitialisation de mot de passe a été envoyé à votre adresse e-mail. Veuillez
                            vérifier votre boîte de réception.
                        </div>';
                    }else { if ($_SERVER['REQUEST_METHOD'] === 'POST')
                        echo '  <div class="alert alert-danger" role="alert">
                            Cette adresse e-mail n\'est pas enregistrée.
                        </div>';
                    }

                        ?>
                </form>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-center links">
                    <a href="login.php">Retour à la connexion</a>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
