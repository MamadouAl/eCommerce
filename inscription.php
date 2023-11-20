<?php
session_start();
require('./util/users.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données du formulaire d'inscription du client
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $motDePasse = $_POST['password'];
    $adresse = $_POST['addresse'];

    // Validation des données
    if (empty($nom) || empty($prenom) || empty($email) || empty($motDePasse) || empty($adresse)) {
        echo "Veuillez remplir tous les champs du formulaire.";
        exit;
    }

    // Hachage du mot de passe
    $motDePasseHache = password_hash($motDePasse, PASSWORD_DEFAULT);

    // Création d'un tableau avec les données du client
    $client = array($nom, $prenom, $email, $motDePasseHache, $adresse);

    // Insertion du client dans la base de données
    $clientId = addClient($client);

    // Vérification si l'inscription a réussi
    if ($clientId) {
        $user = getUserByEmail($email);
        $id = $user['clientid'];
        $_SESSION['clientID'] = $id;
        $_SESSION['nom'] = $user['nom'];
        $_SESSION['prenom'] = $user['prenom'];
        $_SESSION['email'] = $user['email'];
        header("Location: index.php?clientID=$clientId");
        exit;
    } else {
        echo "L'inscription a échoué. Veuillez réessayer.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Inscription</title>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    
    <!--Bootsrap 4 CDN-->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    
    <!--Fontawesome CDN-->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link rel="stylesheet" href="./CSS/sign_in.css">


</head>
<body>
<div class="container"  >
    <img src="images/MAD-logo.png" alt="logo" >

</div>

<div class="container">
    <div class="d-flex justify-content-center h-100">
        <div class="card">
            <div class="card-header">
                <h3>Inscription</h3>
                <div class="d-flex justify-content-end social_icon">
                    <span><i class="fab fa-facebook-square"></i></span>
                    <span><i class="fab fa-google-plus-square"></i></span>
                </div>
            </div>
            <div class="card-body">
                <form method="post">
                    <div class="input-group form-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                        </div>
                        <input type="text" name="nom" class="form-control" placeholder="Nom">
                    </div>
                    <div class="input-group form-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                        </div>
                        <input type="text" name="prenom" class="form-control" placeholder="Prénom">
                    </div>
                    <div class="input-group form-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        </div>
                        <input type="email" name="email" class="form-control" placeholder="Email">
                    </div>
                    <div class="input-group form-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                        </div>
                        <input type="password" name="password" class="form-control" placeholder="Mot de passe">
                    </div>
                    <div class="input-group form-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-address-card"></i></span>
                        </div>
                        <input type="text" name="addresse" class="form-control" placeholder="01 Nom de la Rue, 93400, La ville">
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block">S'inscrire</button>
                    </div>
                </form>
                <div class="d-flex justify-content-center links">
                    Retourner à l'accueil <a href="index.php">Home</a>
                </div>
            </div>
            
        </div>
    </div>
</div>
</body>
</html>
