<?php
session_start();
?>
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

 <?php

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

<body>
<?php
include './include/connexion_bdd.php';
$jour = date("d");
$annee = date('Y');

if ($jour < 10)// test pour savoir sur quel mois on est.
{
    if (date('m') == 1)// test du mois d'une année inférieur
    {
        $mois = 12;//on renvoit vers le mois d'après (décembre 12)
        $annee = $annee - 1;//on renvoit vers l'année précédente
    }
    else
    {
        $mois = date('m') - 1;
    }
}
 else
{
     $mois = date('m');
}

// On vérifie si une fiche de frais existe pour ce mois là
$_SESSION['annee_mois'] = $annee.$mois;
$sql = "select * from fichefrais where idVisiteur = '" .$_SESSION['id']."' and mois='".$_SESSION['annee_mois']."'";
$resultat = $connexion->query($sql);

if (!$ligne = $resultat->fetch())
{
//pas de fiche de frais pour ce mois là on la crée avec les lignes frais forfait correpondante
    $connexion->exec("insert into fichefrais values ('".$_SESSION['id']."', '".$_SESSION['annee_mois']."', 0, 0, NULL, 'CR')");
    $connexion->exec("insert into lignefraisforfait select '".$_SESSION['id']."', '".$_SESSION['annee_mois']."', id, 0 from fraisforfait");
}
?>
<!-- Division pour le contenu principal -->
<div id="contenu">

      <?php
      echo '<h2>Saisie des fiches de frais ' .$_SESSION['annee_mois']. '</h2>' ;
      ?>

    <div class="corpsForm">

    <form method="post" action="frais_forfait.php" >
        <fieldset>
            <legend>Eléments forfaitisés</legend>
            <label for="etape">Forfait Etape : </label>
            <input type="number" name="etape" required=""/></br>
            <label for="km">Frais Kilomètrique : </label>
            <input type="number" name="km" required=""/></br>
            <label for="nuit">Nuitée Hôtel : </label>
            <input type="number" name="nuit" required=""/></br>
            <label for="repas">Repas Restaurant : </label>
            <input type="number" name="repas" required=""/>
        </fieldset>
        <input type="submit" value="Valider"/>
        <input type="reset" value="Réinitialiser"/></br>
    </form>

        <table>
            <tr>
                <th>Forfait Etape</th>
                <th>Frais Kilomètrique</th>
                <th>Nuitée Hôtel</th>
                <th>Repas Restaurant</th>
            </tr>

<?php
$resultatForfaitEtape = $connexion->query('SELECT quantite FROM lignefraisforfait WHERE idVisiteur = "' .$_SESSION['id']. '" AND idFraisForfait = "ETP" AND mois = "' .$_SESSION['annee_mois']. '"');
$forfaitEtape = $resultatForfaitEtape->fetch();
$resultatFraisKm = $connexion->query('SELECT quantite FROM lignefraisforfait WHERE idVisiteur = "' .$_SESSION['id']. '" AND idFraisForfait = "KM" AND mois = "' .$_SESSION['annee_mois']. '"');
$fraisKm = $resultatFraisKm->fetch();
$resultatNuitee = $connexion->query('SELECT quantite FROM lignefraisforfait WHERE idVisiteur = "' .$_SESSION['id']. '" AND idFraisForfait = "NUI" AND mois = "' .$_SESSION['annee_mois']. '"');
$nuitee = $resultatNuitee->fetch();
$resultatRepas = $connexion->query('SELECT quantite FROM lignefraisforfait WHERE idVisiteur = "' .$_SESSION['id']. '" AND idFraisForfait = "REP" AND mois = "' .$_SESSION['annee_mois']. '"');
$repas = $resultatRepas->fetch();
?>

            <h3>Tableau recapitulatif des éléments forfaitisé</h3>
            <tr>
                <td><?php echo $forfaitEtape['quantite']; ?></td>
                <td><?php echo $fraisKm['quantite']; ?></td>
                <td><?php echo $nuitee['quantite']; ?></td>
                <td><?php echo $repas['quantite']; ?></td>
            </tr>
        </table>

    <form method="post" action="frais_hors_forfait.php">
        <fieldset>
            <legend>Frais hors forfait</legend>
            <label for="date">Date : </label>
            <input type="date" name="date" required=""/></br>
            <label for="lib">Libelle : </label>
            <input type="text" name="libelle" required=""/></br>
            <label for="montant">Montant : </label>
            <input type="number" name="montant" required=""/>
        </fieldset>
        <input type="submit" value="Valider"/>
        <input type="reset" value="Réinitialiser"/>
    </form>

        <h3>Tableau recapitulatif des éléments hors forfait</h3>

        <table>
            <tr>
                <th>Date</th>
                <th>Libelle</th>
                <th>Montant</th>
            </tr>

<?php
$resultatHorsForfait = $connexion->query('select libelle, date, montant from lignefraishorsforfait where idVisiteur = "' .$_SESSION['id']. '" and mois = ' .$_SESSION['annee_mois']);
while($horsForfait = $resultatHorsForfait->fetch())
{
?>
            <tr>
                <td><?php echo date("d/m/Y", strtotime($horsForfait['date'])); ?></td>
                <td><?php echo $horsForfait['libelle']; ?></td>
                <td><?php echo $horsForfait['montant']; ?></td>
            </tr>


            <?php
}
            ?>
        </table>


    </div>
</div>
    <!-- Division pour le pied de page -->

<?php include './include/Pied_de_page.html';?>

</body>
</html>
