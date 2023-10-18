<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
</head>
<body>
    <h1>Inscription</h1>
    <?php
    session_start(); // Démarrez ou restaurez la session
    
    require_once("users.php"); // Inclure le fichier de gestion des utilisateurs

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nom = $_POST["nom"];
        $prenom = $_POST["prenom"];
        $email = $_POST["email"];
        $motDePasse = $_POST["motDePasse"];
        $adresseLivraison = $_POST["adresseLivraison"];

        // Vérifier si l'adresse e-mail existe déjà
        if (emailExisteDeja($email)) {
            echo "<p>L'adresse e-mail est déjà associée à un compte. Veuillez utiliser une autre adresse e-mail.</p>";
        } else {
            // Créer un nouveau compte utilisateur
            $clientID = addClient(array($nom, $prenom, $email, $motDePasse, $adresseLivraison));
            if ($clientID) {
                // L'inscription a réussi, connectez automatiquement l'utilisateur
              //  creerSessionUtilisateur($clientID);
                header("Location: index.php"); // Redirigez l'utilisateur vers la page d'accueil ou le tableau de bord
                exit();
            } else {
                echo "<p>Une erreur s'est produite lors de la création du compte. Veuillez réessayer.</p>";
            }
        }
    }
    ?>
    <form method="post" action="">
        <label for="nom">Nom :</label>
        <input type="text" id="nom" name="nom" required><br><br>

        <label for="prenom">Prénom :</label>
        <input type="text" id="prenom" name="prenom" required><br><br>

        <label for="email">Adresse e-mail :</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="motDePasse">Mot de passe :</label>
        <input type="password" id="motDePasse" name="motDePasse" required><br><br>
        
        <label for="adresseLivraison">Adresse de Livraison :</label>
        <input type="text" id="adresseLivraison" name="adresseLivraison" required><br><br>

        <input type="submit" value="S'inscrire">
    </form>
    <p>Déjà inscrit ? <a href="login.php">Connectez-vous ici</a>.</p>
</body>
</html>

