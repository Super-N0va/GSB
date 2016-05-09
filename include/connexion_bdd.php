<?php
    $Hostname = "localhost";
    $NameBDD ="gsb";
    $port = "3306";
    $User ="root";
    $Password = "";
try
{
    $connexion = new PDO ('mysql:host='.$Hostname.';port='.$port.';dbname='.$NameBDD, $User, $Password);

}
catch(Exception $e)
{
    echo 'TEST';
    die('Erreur : '.$e->getMessage());

}
?>
