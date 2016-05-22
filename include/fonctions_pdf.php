<?php

/**
* Fonction qui permet d'afficher la date d'une fiche de frais sur le pdf.
* @param type $connexion  -> variable de connexion à la base de données
*/
function afficheDate($connexion){

  $tabMois = array("Janvier", "Février", "Mars", "Avril", "Mais", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre");
  $leMois = substr($_SESSION['mois'], 4,2);
  $leMois = $tabMois[intval($leMois)-1];

  $lAnnee = substr($_SESSION['mois'], 0,4);

  Mois : echo $leMois." ".$lAnnee;
}

/**
* Fonction qui ajoute la signature du comptable dans le pdf.
* @param type $connexion  -> variable de connexion à la base de données
*/
function signaturePdf($connexion){
  $leMois = date("m");
  $tabMois = array("Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre",
  "Octobre", "Novembre", "Décembre");
  $mois = $tabMois[intval($leMois)-1];
  echo "<br />Fait à Le Mans, le ".date("d")." ".$mois." ".date("Y")."<br/> Vu l'agent comptable : ";
}

?>
