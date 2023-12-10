<?php
session_start();
require('./util/users.php');
$connexion = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données du formulaire
    $email = $_POST['email'];
    $motDePasse = $_POST['password'];

    // Obtenez le hachage du mot de passe stocké dans la base de données
    $user = getUserByEmail($email);
    $hashedPassword = $user['passwd'];

    // Vérifiez le mot de passe avec password_verify
    if (password_verify($motDePasse, $hashedPassword)) {
        // Mot de passe correct

        $id = $user['clientid'];
        $_SESSION['clientID'] = $id;
        $_SESSION['nom'] = $user['nom'];
        $_SESSION['prenom'] = $user['prenom'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];
        $connexion = true;

        // vérification de l'adresse email de l'administrateur
        if ($_SESSION['role'] === 'admin') {
            header("Location: ./admin/admin.php");
            exit;
        }

        if (isset($_SESSION['page_avant_login'])) {
            // Redirigez l'utilisateur vers la page précédente
            header('Location: ' . $_SESSION['page_avant_login']);
            exit;
        }

        // Redirigez l'utilisateur vers index.php
        header("Location: index.php");
        exit;
    } else {
        // Mot de passe incorrect
        $connexion = false;
    }
}
?>




<!DOCTYPE html>
<html lang="fr">
<head>
	<title>Connexion</title>
   <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
   
	<!--Bootsrap 4 CDN-->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    
    <!--Fontawesome CDN-->
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link rel="stylesheet" href="./CSS/login.css">


</head>
<body>
<div class="container">
	<div class="d-flex justify-content-center h-100">
		<div class="card">
			<div class="card-header">
				<h3>Connexion</h3>
				<div class="d-flex justify-content-end social_icon">
					<span><i class="fab fa-facebook-square"></i></span>
					<span><i class="fab fa-google-plus-square"></i></span>
				</div>
			</div>
			<div class="card-body">
				<form method="post">
                    <div class="card-img-top" >
                        <h3>MAD SHOP</h3>
                        <a href="index.php">
                        <img src="./images/MAD-logo.png" alt="logo site web" width="50%" height="50%">
                        </a>
                    </div>

					<div class="input-group form-group">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="fas fa-user"></i></span>
						</div>
						<input type="email" name="email" class="form-control" placeholder="email" required>

					</div>
					<div class="input-group form-group">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="fas fa-key"></i></span>
						</div>
						<input type="password" name="password" class="form-control" placeholder="Mot de pass" required>
					</div>
					<div class="row align-items-center remember">
                        <label>
                            <input type="checkbox">
                        </label>Se souvenir de moi
					</div>
					<div class="form-group">
						<button type="submit" class="btn btn-primary btn-block">Se connecter</button>
                    <?php
                        if (!$connexion) {
                            if ($_SERVER['REQUEST_METHOD'] === 'POST')
                            echo '<div class="alert alert-danger" role="alert">
                            La connexion a échoué. Vérifiez vos informations d\'identification.
                            </div>';
                        }
                    ?>


					</div>
				</form>
                <div class="d-flex links">
                    <p>Vous n'avez pas de compte ?<a href="inscription.php">Créer un compte</a></p><br>
                    <p><a href="resetPasswd.php">Mot de pass oublié ?</a></p>
                </div>
			</div>

		</div>
	</div>
</div>
</body>
</html>