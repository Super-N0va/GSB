<!doctype html>
<html lang='fr'>
<head>
   <meta charset='utf-8'>
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <link rel="stylesheet" href="./styles/stylemenu.css">
   <!--<link rel="stylesheet" href="design.css">-->
   <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
   <!-- <script src="script.js"></script>  -->
   <title>Espace personnel - GSB</title>
</head>
<body>
    <?php
        session_start();
        include './include/connexion_bdd.php';
        header( 'content-type: text/html; charset=utf-8' );
    ?>

     <?php
        if(!isset($_SESSION['nom']) && !isset($_SESSION['prenom'])){
          header('Location: index.php?message=Veuillez vous connecter pour pouvoir accéder à cette page');
        }

       echo "<h2 class='titre'>".$_SESSION['nom']." ".$_SESSION['prenom']."</h2>";
     ?>
    <br>
    <h1 class="titre">Visiteur médical</h1>
    <br>

<div id='cssmenu'>
<ul>
   <li><a href='espacePerso.php'><span>Fiches de frais</span></a></li>
   <li><a href='saisie_frais.php'><span>Saisie fiche de frais</span></a></li>
   <li class='last'><a href='deconnexion.php'><span>Se déconnecter</span></a></li>
</ul>
</div>

    <div id="body">
    <h3 class="titre"><b>Mois à selectionner :</h3></b>
    <form action="espacePerso.php" method="POST">
    <fieldset>
        <center><label for="mois">Mois :</label>
            <select name="mois">
                    <?php
                        $resultatRecherche = $connexion->query('SELECT DISTINCT mois FROM fichefrais ORDER BY mois DESC');



                        while($Mois = $resultatRecherche->fetch())
                        {
                            $tabMois = array("Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre");
                            $leMois = substr($Mois['mois'], 4,2);
                            $leMois = $tabMois[intval($leMois)-1];
                            $lAnnee = substr($Mois['mois'], 0, 4);
                    ?>

                        ?>
                   <option value="<?php echo $Mois['mois'] ?>"><?php echo $leMois. " " .$lAnnee ."\n"; ?></option>

                <?php
                        }
                    $resultatRecherche->closeCursor();
                ?>
            </select>
        </center>
    </fieldset>

    <br>
    <input type="submit" value="Valider"></input>
    <input type="reset" value="Effacer"></input>


</form>

<br>

</div>
 <?php
if (isset($_POST['mois']))
{
    $_SESSION['mois']=$_POST['mois'];

    $tabMois = array("Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre");
    $leMois = substr($_POST['mois'], 4,2);
    $leMois = $tabMois[intval($leMois)-1];

    $lAnnee = substr($_POST['mois'], 0,4);

    $resultatEtat = $connexion->query("SELECT libelle, dateModif
                         FROM etat E
                         INNER JOIN fichefrais FF ON E.id = FF.idEtat
                         WHERE FF.idVisiteur = '".$_SESSION['id']."'
                         AND FF.mois = '".$_POST['mois']."'");
    $typeEtat = $resultatEtat->fetch();

    $etat = $typeEtat['libelle'];
    $date = $typeEtat['dateModif'];

    $resultatEtat->closeCursor();

    echo "Fiche de frais du mois de ".$leMois." ".$lAnnee." : ".utf8_encode($etat). " depuis le ".$date;
    $resultat = $connexion->query('SELECT montantValide
                                 FROM fichefrais
                                 WHERE idVisiteur="'.$_SESSION['id'].'"
                                 AND mois = "'.$_POST['mois'].'"');
if ($ligne = $resultat->fetch())
{
    $montant = $ligne['montantValide'];
    echo '<br/><br/>';
    echo 'Montant validé : '.$montant.'';
}
?>

    <br>
    <br>
    <h2 class="titre">Quantités des elements forfaitisés</h2>

    <table width='100%' cellspacing='0' cellpadding='0' align='center'>
      <tr>
      <td colspan='1' align='center'>Forfait Etape</td>
      <td colspan='1' align='center'>Frais Kilométrique</td>
      <td colspan='1' align='center'>Nuitées Hôtel</td>
      <td colspan='1' align='center'>Repas Restaurant</td>
   </tr>
   <tr>

 <?php
$resultat2 = $connexion->query('SELECT quantite
                              FROM lignefraisforfait
                              WHERE idVisiteur="'.$_SESSION['id'].'"
                              AND mois = "'.$_POST['mois'].'"');
while($ligne = $resultat2->fetch())
  {
    $idfrais = $ligne['quantite'];
    echo  "<td width='25%' align='center'>".$idfrais."</td>";
 }
 ?>


</tr></table>
<?php
$resultat2->closeCursor();
$resultat3 = $connexion->query('SELECT DATE, montant, libelle
                              FROM lignefraishorsforfait
                              WHERE mois="'.$_POST['mois'].'"
                              AND idVisiteur="'.$_SESSION['id'].'" order by mois desc');
?>

<br>
<h2 class="titre">Descriptif des éléments hors forfaits</h2>

<table width='100%' cellspacing='0' cellpadding='0' align='center'>
      <tr>
      <td colspan='1' align='center'>Date</td>
      <td colspan='1' align='center'>Libelle</td>
      <td colspan='1' align='center'>Montant</td>
      </tr>

<?php
 while($ligne=$resultat3->fetch())

 {
    $date = $ligne['DATE'];
    $montant = $ligne['montant'];
    $libelle = $ligne['libelle'];

      echo "
     <tr>
         <td width='20%' align='center'>".$date."</td>
         <td width='60%' align='center'>".utf8_encode($libelle)."</td>
         <td width='20%' align='center'>".$montant."</td>
     </tr>";

 }
}



?>

</table>
<br/><br/>
<a href="pdf.php">Imprimer en PDF</a>

</div>



</body>

</html>
