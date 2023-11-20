<?php
include "./util/users.php";

// Inclure d'autres fichiers ou configurations nécessaires

// Vérifier si le token est présent dans l'URL
if (isset($_GET['token'])) {
    $resetToken = $_GET['token'];

    $id = verifToken($resetToken);

    if ($user = getUserById($id)) {
        // Afficher le formulaire de réinitialisation du mot de passe
        // et traiter le formulaire lorsque l'utilisateur soumet un nouveau mot de passe
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nouveauMotDePasse = $_POST['nouveau_mot_de_passe'];

            // Vous devrez avoir une fonction pour mettre à jour le mot de passe
            // Cette fonction devrait également invalider le token après utilisation
            changePassword($id, $nouveauMotDePasse);

            // Afficher un message de succès ou rediriger vers la page de connexion
            echo '<div class="container mt-5">
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="alert alert-success" role="alert">
                                        Mot de passe mis à jour avec succès !
                                    </div>
                                    <p>Vous pouvez maintenant <a href="login.php">vous connecter</a> avec votre nouveau mot de passe.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';
        } else {
            // Afficher le formulaire de réinitialisation du mot de passe

            ?>
            <!DOCTYPE html>
            <html lang="fr">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
                <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet">
                <title>Réinitialisation du Mot de Passe</title>
            </head>
            <body>

            <div class="container mt-5">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Réinitialisation du Mot de Passe</h5>
                                <form method="post">
                                    <div class="form-group">
                                        <label for="nouveau_mot_de_passe">Nouveau Mot de Passe :</label>
                                        <input type="password" class="form-control" name="nouveau_mot_de_passe" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Réinitialiser le Mot de Passe</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            </body>
            </html>
            <?php
        }
    } else {
        // Token invalide, afficher un message d'erreur ou rediriger vers une autre page
        echo '<div class="container mt-5">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="alert alert-danger" role="alert">
                                    Ce lien de réinitialisation n\'est pas valide.
                                </div>
                                <p>Veuillez vérifier le lien ou <a href="resetPasswd.php">demander un nouveau lien de réinitialisation</a>.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>';
    }
} else {
    // Token non présent dans l'URL, afficher un message d'erreur ou rediriger vers une autre page
    echo '<div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="alert alert-danger" role="alert">
                                Lien de réinitialisation manquant.
                            </div>
                            <p>Veuillez <a href="resetPasswd.php">demander un nouveau lien de réinitialisation</a>.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>';
}
?>
