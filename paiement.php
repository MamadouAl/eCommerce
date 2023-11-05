<?php
session_start();
$_SESSION['page_avant_login'] = $_SERVER['REQUEST_URI'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Page de Paiement</h1>
    <div class="row">
        <div class="col-md-6">
            <h3>Informations de Livraison</h3>
            <form>
                <div class="mb-3">
                    <label for="nom" class="form-label">Nom</label>
                    <input type="text" class="form-control" id="nom" placeholder="Nom">
                </div>
                <div class="mb-3">
                    <label for="adresse" class="form-label">Adresse de Livraison</label>
                    <input type="text" class="form-control" id="adresse" placeholder="Adresse de Livraison">
                </div>
                <div class="mb-3">
                    <label for="ville" class="form-label">Ville</label>
                    <input type="text" class="form-control" id="ville" placeholder="Ville">
                </div>
                <div class="mb-3">
                    <label for="code_postal" class="form-label">Code Postal</label>
                    <input type="text" class="form-control" id="code_postal" placeholder="Code Postal">
                </div>
            </form>
        </div>
        <div class="col-md-6">
            <h3>Détails de Paiement</h3>
            <form>
                <div class="mb-3">
                    <label for="carte" class="form-label">Numéro de Carte</label>
                    <input type="text" class="form-control" id="carte" placeholder="Numéro de Carte">
                </div>
                <div class="mb-3">
                    <label for="date_expiration" class="form-label">Date d'Expiration</label>
                    <input type="text" class="form-control" id="date_expiration" placeholder="MM/YY">
                </div>
                <div class="mb-3">
                    <label for="code_securite" class="form-label">Code de Sécurité</label>
                    <input type="text" class="form-control" id="code_securite" placeholder="Code de Sécurité">
                </div>
                <button type="submit" class="btn btn-primary">Payer</button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
</body>
</html>
