<!DOCTYPE HTML>
<html>
<header>
  <meta charset="utf-8">
  <link rel = "stylesheet" href ="./styles/design.css">


  <title>Accueil GSB</title>


</header>
<body>
  <?php
    require ('include/entete.html');
  ?>

  <?php
  if(isset($_SESSION['pseudo']))
  {
    echo "Connecté : ".$_SESSION['pseudo'];
  }


  if (isset($_GET['message'])){
    echo $_GET['message'];
  }

  ?>
  <br>
  <br>

  <div id = "image">
    <!--<center><u><h1 class="titre">L'entreprise GSB</h1></u></center>-->
    <h2 id="titre">
    </br><div class="titre"><u><h1>L'entreprise GSB</h1></u></div>
  </h2>
  <p><img src="image/logo.png" class="imagelogo" alt="GSB" class="img-rounded"></p>
</br>
</br>

<p class="texte">Le laboratoire Galaxy Swiss Bourdin (GSB) est issu de la fusion entre le géant américain Galaxy (spécialisé dans
  le secteur des maladies virales dont le SIDA et les hépatites) et le conglomérat européen Swiss Bourdin (travaillant
  sur des médicaments plus conventionnels), lui même déjà union de trois petits laboratoires .
  En 2009, les deux géants pharmaceutiques ont uni leurs forces pour créer un leader de ce secteur industriel.
  L'entité Galaxy Swiss Bourdin Europe a établi son siège administratif à Paris.
  Le siège social de la multinationale est situé à Philadelphie, Pennsylvanie, aux Etats-Unis.
  La France a été choisie comme témoin pour l'amélioration du suivi de l'activité de visite.
</p></div>


</body>


<?php
  require ('include/pied_de_page.html');
?>
</html>
