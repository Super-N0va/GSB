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
        require ('./include/connexion_bdd.php');
        require ('./include/fonctions.php');
        header( 'content-type: text/html; charset=utf-8' );
    ?>

     <?php
      checkConnection();
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
// On vérifie si une fiche de frais existe pour ce mois là
  creationFicheFrais($connexion, $_SESSION['id']);
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
  recuperationElementsForfaitises($connexion, $_SESSION['id']);
?>

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
  recuperationElementsHorsForfait($connexion, $_SESSION['id']);
?>
        </table>
    </div>
</div>
    <!-- Division pour le pied de page -->

<?php include ('./include/Pied_de_page.html');?>

</body>
</html>
