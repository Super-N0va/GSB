<!DOCTYPE html>
<html>
    <header>
        <meta charset="utf-8">
        <link rel="stylesheet" href="./styles/design.css" />

    </header>
    <body>


          <?php
        include('include/entete.html');
        ?>
        <br>
        <h2 id="titre">
            <u></br><div class="titre"><h1>Liste des médecins</h1></br></div></u>
        </h2>

<?php
require ('./include/connexion_bdd.php');

$resultats=$connexion->query("SELECT * FROM Medecins");

echo '<center><table border=2 cellpadding=20>
       <th>Nom</th>
       <th> Prenom </th>
       <th> Adresse </th>
       <th style="white-space:nowrap;" > Numero de Rue </th>
       <th> Ville </th>
       <th> Adresse Email </th>
       ';

while ($ligne = $resultats->fetch() )
{
    echo '<tr>
       <td>'.$ligne['nom']. '</td>
       <td>' .$ligne['prenom']. '</td>
       <td>' .$ligne['adresse']. '</td>
       <td>' .$ligne['numRue']. '</td>
       <td style="white-space:nowrap;">' .$ligne['ville']. '</td>
       <td>' .$ligne['adresseMail']. '</td>

           </tr>'


;}


echo '</table></center>'
?>

        <?php
        include('include/pied_de_page.html');
        ?>


    </body>
