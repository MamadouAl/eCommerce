<?php
/**
 * Fichier de connexion à la base de données
 */

/* Variables de connexion aux bases de données */
$_ENV['dbHost']='localhost';
$_ENV['dbName']='dm213333';
$_ENV['dbUser']='dm213333';
$_ENV['dbPasswd']='20213333';

/**
 * Connexion à la base de données
 * @return resource
 */
function connexion() {
    $strConnex = "host=".$_ENV['dbHost']
             ." dbname=".$_ENV['dbName']
            ." user=".$_ENV['dbUser']
        ." password=".$_ENV['dbPasswd'];
    return pg_connect($strConnex);
}

