<?php
session_start();
require('./util/users.php');

if (!isset($_SESSION['clientID'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['clientID'];
$user = getUserByID($userId);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
// Gestion de l'upload de la photo de profil
    if (!empty($_FILES["image"]["name"])) {
        $target_dir = "./images/users/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);

// Vérifier si le fichier a été téléchargé avec succès
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
// Mettre à jour le chemin de la photo de profil dans la base de données
            ajouteProfil_img($userId, $target_file);
        } else {
            echo "Une erreur s'est produite lors du téléchargement de votre fichier.";
        }
    }

// Récupération des données du formulaire
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $adresse = $_POST['adresse'];

// Assurez-vous de valider et échapper les données avant de les utiliser dans une requête SQL.
    $isModifiee = updateUser($userId, $nom, $prenom, $email, $adresse);

// Vérification si la modification a réussi
    if ($isModifiee) {
        header("Location: monProfil.php");
        exit;
    } else {
        echo "La modification a échoué. Veuillez réessayer.";
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis à jour</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <!--Fontawesome CDN-->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link rel="stylesheet" href="./CSS/header.css">  </head>

<style>
        #iciProfil {
            color: white;
            border-bottom: solid;
        }
    </style>
</head>
<body>
<header>
    <?php include('./includes/monHeader.php'); //  ?>
</header>
<div class="container mt-5">
    <h1 class="mb-4">Mis à jour</h1>
    <div class="row">
        <div class="col-md-6">
            <h3>Vos Informations</h3>
            <form method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="photoProfil" class="form-label">Photo de Profil</label>
                    <input type="file" class="form-control" id="image" name="image">
                   <!-- $<input type="submit" value="Télécharger" name="submit"> -->
                </div>

                <div class="mb-3">
                    <label for="nom" class="form-label">Nom</label>
                    <input type="text" class="form-control" id="nom" name="nom" value="<?php echo $user['nom'] ?>">
                </div>
                <div class="mb-3">
                    <label for="prenom" class="form-label">Prénom</label>
                    <input type="text" class="form-control" id="prenom" name="prenom" value="<?php echo $user['prenom'] ?>">
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Adresse Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo $user['email'] ?>">
                </div>
                <div class="mb-3">
                    <label for="adresse" class="form-label">Adresse de Livraison</label>
                    <input type="text" class="form-control" id="adresse" name="adresse" value="<?php echo $user['adresselivraison'] ?>">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block">Modifier</button>
                    <button type="reset" class="btn btn-primary bg-danger">Annuler</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
</body>
</html>
