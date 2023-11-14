<?php
/*
if (isset($_SESSION['page_avant_login'])) {
    // Redirigez l'utilisateur vers la page précédente
    header('Location: ' . $_SESSION['page_avant_login']);
    exit;
}
*/
session_start();
require('./util/users.php'); // Inclure votre fichier de fonctions

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données du formulaire
    $email = $_POST['email'];
    $motDePasse = $_POST['password'];

    if (loginUser($email, $motDePasse)) {
        $user = getUserByEmail($email);
        $id=$user['clientid'];
        $_SESSION['clientID'] = $id;


        // vérification de l'adresse email de l'administrateur
        $adminEmail = 'tygaaliou@lehavre.fr'; //adresse email de l'administrateur
        if ($email === $adminEmail) {
            $_SESSION['admin'] = true;
            header("Location: ./admin/admin.php");
            exit;
        }

        if (isset($_SESSION['page_avant_login'])) {
            // Redirigez l'utilisateur vers la page précédente
            header('Location: ' . $_SESSION['page_avant_login']);
            exit;
        }

        // Redirigez l'utilisateur vers index.php
        header("Location: index.php?clientID=$id");
        exit;
    } else {
        // La connexion a échoué, affichez un message d'erreur ou effectuez d'autres actions.
        echo "La connexion a échoué. Vérifiez vos informations d'identification.";
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

<style> 

@import url('https://fonts.googleapis.com/css?family=Numans');

html,body{
background-image: url('https://getwallpapers.com/wallpaper/full/1/9/d/31242.jpg');
/*http://getwallpapers.com/wallpaper/full/a/5/d/544750.jpg */
background-size: cover;
background-repeat: no-repeat;
height: 100%;
font-family: 'Numans', sans-serif;
}

.container{
    height: 100%;
    align-content: center;
}

.card{
    height: 550px;
    margin-top: auto;
    margin-bottom: auto;
    width: 405px;
    background-color: rgba(0,0,0,0.5) !important;
}

.social_icon span{
    font-size: 60px;
    margin-left: 10px;
    color: #FFC312;
}

.social_icon span:hover{
    color: white;
    cursor: pointer;
}

.card-header h3{
color: white;
}

.card-img-top{
    text-align: center;
    margin-bottom: 20px;
    color: white;

}

.social_icon{
    position: absolute;
    right: 20px;
    top: -45px;
}

.input-group-prepend span{
    width: 50px;
    background-color: #FFC312;
    color: black;
    border:0 !important;
}

input:focus{
/*outline: 0 0 0 0 !important;*/
box-shadow: 0 0 0 0 !important;

}

.remember{
color: white;
}

.remember input
{
width: 20px;
height: 20px;
margin-left: 15px;
margin-right: 5px;
}


.links{
color: white;
}

.links a{
margin-left: 4px;
}
</style>
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

					</div>
				</form>
			</div>
			<div class="card-footer">
				<div class="d-flex justify-content-center links">
					Vous n'avez pas de compte ?<a href="inscription.php">M\'inscrire</a>
				</div>
				<div class="d-flex justify-content-center">
					<a href="resetPasswd.php">Mot de pass oublié ?</a>
				</div>
			</div>
		</div>
	</div>
</div>
</body>
</html>