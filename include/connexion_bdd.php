<?php
    $Hostname = "host";
    $NameBDD ="namebdd";
    $port = "3306";
    $User ="login";
    $Password = "password";
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
