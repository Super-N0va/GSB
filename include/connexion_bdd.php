<?php
    $Hostname = "172.16.99.3";
    $NameBDD ="s.gilbert";
    $port = "3306";
    $User ="s.gilbert";
    $Password = "trgb3zbj";
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
