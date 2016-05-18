<?php


function getSheet($connexion){

  $tabMois = array("Janvier", "Février", "Mars", "Avril", "Mais", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre");
  $leMois = substr($_SESSION['mois'], 4,2);
  $leMois = $tabMois[intval($leMois)-1];
  $lAnnee = substr($_SESSION['mois'], 0,4);
  $resultat=$connexion->query('SELECT montantValide from fichefrais where idVisiteur="'.$id.'" and mois = "'.$mois.'"');
}

function fetchDate($connexion){

  $tabMois = array("Janvier", "Février", "Mars", "Avril", "Mais", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre");
  $leMois = substr($_SESSION['mois'], 4,2);
  $leMois = $tabMois[intval($leMois)-1];

  $lAnnee = substr($_SESSION['mois'], 0,4);

  Mois : echo $leMois." ".$lAnnee;
}


function signaturePdf($connexion){
  $leMois = date("m");
  $tabMois = array("Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre",
  "Octobre", "Novembre", "Décembre");
  $mois = $tabMois[intval($leMois)-1];
  echo "<br />Fait à Le Mans, le ".date("d")." ".$mois." ".date("Y")."<br/> Vu l'agent comptable : ";
}

?>
