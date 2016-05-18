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
        require ('./include/connexion_bdd.php');
        require ('./include/fonctions.php');
        header ('content-type: text/html; charset=utf-8');
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

    <div id="body">
    <h3 class="titre"><b>Mois à selectionner :</h3></b>
    <form action="espacePerso.php" method="POST">
    <fieldset>
        <center>
        <label for="mois">Mois : </label>
            <select name="mois">
                <?php
                    monthListDisplay($connexion);
                ?>
            </select>
        </center>
        </fieldset>

        <input type="submit" value="Valider"></input>
        <input type="reset" value="Effacer"></input>
    </form>

    <?php
// Si on choisit une date dans la liste déroulante et valide on affiche
if(isset($_POST['mois']) != false) {
    sheetState($connexion, $_POST['mois'], $_SESSION['id']);
    amountSheet($connexion, $_SESSION['id']);
    ?>

    <br/><br/>
    <h2>Quantités des elements forfaitisés</h2>
        <table width='100%' cellspacing='0' cellpadding='0' align='center'>
            <tr>
                <td colspan='1' align='center'>Forfait Etape</td>
                <td colspan='1' align='center'>Frais Kilométrique</td>
                <td colspan='1' align='center'>Nuitées Hôtel</td>
                <td colspan='1' align='center'>Repas Restaurant</td>
            </tr>
            <tr>
            <?php
                lineExceptPackageTable($connexion, $_SESSION['id']);
             ?>
            </tr>
        </table>
    <br/>
    <h2>Descriptif des éléments hors forfaits</h2>
    <table width='100%' cellspacing='0' cellpadding='0' align='center'>
          <tr>
          <td colspan='1' align='center'>Date</td>
          <td colspan='1' align='center'>Libelle</td>
          <td colspan='1' align='center'>Montant</td>
          </tr>
    <?php
        lineExceptPackageTable($connexion, $_SESSION['id']);
    ?>
    </table>
    <br/><br/>
    <a href="pdf.php">Imprimer en PDF</a>
    <?php
}
?>
</div>
    <!-- Division pour le pied de page -->

<?php
  require ('./include/pied_de_page.html');
?>


    </body>
</html>
