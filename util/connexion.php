<?php

/* Variables de connexion aux bases de données */
$_ENV['dbHost']='localhost';
$_ENV['dbName']='dm123456';
$_ENV['dbUser']='dm123456';
$_ENV['dbPasswd']='12345678';

function connexion() {
     
    $strConnex = "host=".$_ENV['dbHost']." dbname=".$_ENV['dbName']." user=".$_ENV['dbUser']." password=".$_ENV['dbPasswd'];

    return pg_connect($strConnex);
}
?>
