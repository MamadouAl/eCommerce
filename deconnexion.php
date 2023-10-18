<?php
session_start();

if (isset($_SESSION['clientID'])){

    $_SESSION['clientID'] = array();

    session_destroy();
    echo "Deconnexion réussi !";
    header("Location: index.php");
}

?>