<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
</head>
<body>
    <h1>Connexion</h1>
    <?php
    session_start(); // Démarrez ou restaurez la session
    
    require_once("users.php"); // Inclure le fichier de gestion des utilisateurs
    require_once("admin.php");

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST["email"];
        $motDePasse = $_POST["motDePasse"];

        // Vérifier l'authentification de l'utilisateur
        $clientID = loginUser($email, $motDePasse);

        if ($clientID) {
            // L'utilisateur est authentifié avec succès, connectez-le
            //header("Location: index.php"); // Redirigez l'utilisateur vers la page d'accueil ou le tableau de bord
            //exit();
            echo "<h1> Tout va bien </h1>";
        } else {
            // Authentification échouée, affichez un message d'erreur
            echo "<p>Identifiants incorrects. Veuillez réessayer.</p>";
        }
    }
    ?>
    <form method="post" action="">
        <label for="email">Adresse e-mail :</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="motDePasse">Mot de passe :</label>
        <input type="password" id="motDePasse" name="motDePasse" required><br><br>

        <input type="submit" value="Se connecter">
    </form>
    <p>Vous n'avez pas de compte ? <a href="inscription.php">Inscrivez-vous ici</a>.</p>
</body>
</html>

