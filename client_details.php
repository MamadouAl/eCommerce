<?php
include './util/connexion.php';


if (isset($_GET['clientID'])) {
    $clientID = $_GET['clientID'];
    $connected = connexion();

    // Récupérez les informations du client
    $queryClient = "SELECT * FROM client WHERE clientID = $1";
    pg_prepare($connected, "get_client", $queryClient);
    $resultClient = pg_execute($connected, "get_client", array($clientID));

    if ($resultClient && $rowClient = pg_fetch_assoc($resultClient)) {
        $nom = $rowClient['nom'];
        $prenom = $rowClient['prenom'];
        $email = $rowClient['email'];
        $adresseLivraison = $rowClient['adresselivraison'];
        ?>

        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Détails du Client</title>
        </head>
        <body>
            <h1>Détails du Client</h1>
            <table>
                <tr>
                    <td><strong>ID du Client :</strong></td>
                    <td><?php echo $clientID; ?></td>
                </tr>
                <tr>
                    <td><strong>Nom :</strong></td>
                    <td><?php echo $nom; ?></td>
                </tr>
                <tr>
                    <td><strong>Prénom :</strong></td>
                    <td><?php echo $prenom; ?></td>
                </tr>
                <tr>
                    <td><strong>Email :</strong></td>
                    <td><?php echo $email; ?></td>
                </tr>
                <tr>
                    <td><strong>Adresse de Livraison :</strong></td>
                    <td><?php echo $adresseLivraison; ?></td>
                </tr>
            </table>

            <h2>Commandes du Client</h2>
            <?php
            $queryCommandes = "SELECT * FROM commande WHERE clientID = $1";
            pg_prepare($connected, "get_commandes", $queryCommandes);
            $resultCommandes = pg_execute($connected, "get_commandes", array($clientID));

            if ($resultCommandes) {
                while ($rowCommande = pg_fetch_assoc($resultCommandes)) {
                    $commandeID = $rowCommande['commandeid'];
                    $dateCommande = $rowCommande['datecommande'];
                    $statut = $rowCommande['statut'];
                    ?>
                    <h3>Commande ID : <?php echo $commandeID; ?></h3>
                    <p><strong>Date de Commande :</strong> <?php echo $dateCommande; ?></p>
                    <p><strong>Statut :</strong> <?php echo $statut; ?></p>

                    <!-- Afficher les détails de la commande sous forme de panier -->
                    <table border="1">
                        <tr>
                            <th>Produit</th>
                            <th>Quantité</th>
                        </tr>
                        <?php
                        $queryDetailsCommande = "SELECT produit.nom AS produit, produit_commande.quantite FROM produit_commande JOIN produit ON produit_commande.produitid = produit.produitid WHERE produit_commande.commandeid = $1";
                        $preparedName = "get_commande_details_" . $commandeID; // Utilisez un nom unique basé sur l'ID de la commande
                        pg_prepare($connected, $preparedName, $queryDetailsCommande);
                        $resultDetailsCommande = pg_execute($connected, $preparedName, array($commandeID));
                        
                        if ($resultDetailsCommande) {
                            while ($rowDetailCommande = pg_fetch_assoc($resultDetailsCommande)) {
                                $produit = $rowDetailCommande['produit'];
                                $quantite = $rowDetailCommande['quantite'];
                                ?>
                                <tr>
                                    <td><?php echo $produit; ?></td>
                                    <td><?php echo $quantite; ?></td>
                                </tr>
                                <?php
                            }
                        } else {
                            echo "<tr><td colspan='2'>Aucun détail de commande trouvé.</td></tr>";
                        }
                        ?>
                    </table>
                    <?php
                }
            } else {
                echo "<p>Aucune commande trouvée pour ce client.</p>";
            }
            ?>
        </body>
        </html>
        <?php
    } else {
        echo "Client non trouvé.";
    }
} else {
    echo "ID du client non spécifié.";
}
?>

