<?php

function checkConnection() {
  if(!isset($_SESSION['nom']) && !isset($_SESSION['prenom'])){
    header('Location: index.php?message=Veuillez vous connecter pour pouvoir accéder à cette page');
  }

  echo "<h2 class='titre'>".$_SESSION['nom']." ".$_SESSION['prenom']."</h2>";
}

function monthListDisplay($connexion){
  $resultatRecherche = $connexion->query('SELECT DISTINCT mois FROM fichefrais ORDER BY mois DESC');
  while($Mois = $resultatRecherche->fetch())
  {
    $tabMois = array("Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre");
    $leMois = substr($Mois['mois'], 4,2);
    $leMois = $tabMois[intval($leMois)-1];
    $lAnnee = substr($Mois['mois'], 0,4);
    ?>
    <option value="<?php echo $Mois['mois'] ?>"><?php echo $leMois. " " .$lAnnee . "\n"; ?></option>
    <?php
  }
  $resultatRecherche->closeCursor();
}

function sheetState($connexion, $mois, $id) {
  $_SESSION['mois']=$mois;
  $tabMois = array("Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre");
  $leMois = substr($_POST['mois'], 4,2);
  $leMois = $tabMois[intval($leMois)-1];

  $lAnnee = substr($_POST['mois'], 0,4);

  $resultatEtat = $connexion->query('SELECT libelle, dateModif
    FROM etat E
    INNER JOIN fichefrais FF ON E.id = FF.idEtat
    WHERE FF.idVisiteur = "'.$id.'"
    AND FF.mois = "'.$_POST['mois'].'"');
    while($typeEtat = $resultatEtat->fetch()) {
      $etat = $typeEtat['libelle'];
      $date = $typeEtat['dateModif'];
    }
    $resultatEtat->closeCursor();

    echo "Fiche de frais du mois de ".$leMois." ".$lAnnee." : ".utf8_encode($etat). " depuis le ".$date;
  }

  function amountSheet($connexion, $id) {
    $resultat = $connexion->query('SELECT montantValide
      FROM fichefrais
      WHERE idVisiteur="'.$id.'"
      AND mois = "'.$_POST['mois'].'"');
      if ($ligne = $resultat->fetch()) {
        $montant = $ligne['montantValide'];
        echo '<br/><br/>';
        echo 'Montant validé : '.$montant.'';
      }
    }

    function linePackageTable($connexion, $id) {
      $resultat2 = $connexion->query('SELECT quantite
        FROM lignefraisforfait
        WHERE idVisiteur="'.$id.'"
        AND mois = "'.$_POST['mois'].'"');
        while($ligne = $resultat2->fetch()) {
          $idfrais = $ligne['quantite'];
          echo  "<td width='25%' align='center'>".$idfrais."</td>";
        }

        $resultat2->closeCursor();
      }

      function lineExceptPackageTable($connexion, $id) {
        $resultat3 = $connexion->query('SELECT DATE, montant, libelle
          FROM lignefraishorsforfait
          WHERE mois="'.$_POST['mois'].'"
          AND idVisiteur="'.$id.'" order by mois desc');

          while($ligne=$resultat3->fetch()) {
            $date = $ligne['DATE'];
            $montant = $ligne['montant'];
            $libelle = $ligne['libelle'];
            echo "
            <tr>
            <td width='20%' align='center'>$date</td>
            <td width='60%' align='center'>$libelle</td>
            <td width='20%' align='center'>$montant</td>
            </tr>";
          }

          $resultat3->closeCursor();
        }

        function creationFicheFrais($connexion, $id){
          // Initialisation du jour et du mois
          $jour = date("d");
          $annee = date('Y');
          $mois = date('m');
          // On vérifie si une fiche de frais existe pour ce mois là
          $_SESSION['annee_mois'] = $annee.$mois;
          $sql = "select * from fichefrais where idVisiteur = '" .$id."' and mois='".$_SESSION['annee_mois']."'";
          $resultat = $connexion->query($sql);
          if (!$ligne = $resultat->fetch())
          {
            $date = date('YYYY-MM-DD');
            //pas de fiche de frais pour ce mois là on la crée avec les lignes frais forfait correpondante
            $connexion->exec("insert into fichefrais values ('".$id."', '".$_SESSION['annee_mois']."', 0, 0, $date, 'CR')");
            $connexion->exec("insert into lignefraisforfait select '".$id."', '".$_SESSION['annee_mois']."', id, 0 from fraisforfait");
          }
        }

        function recuperationElementsForfaitises($connexion, $id){
          $resultatForfaitEtape = $connexion->query('SELECT quantite FROM lignefraisforfait WHERE idVisiteur = "' .$id. '" AND idFraisForfait = "ETP" AND mois = "' .$_SESSION['annee_mois']. '"');
          $forfaitEtape = $resultatForfaitEtape->fetch();

          $resultatFraisKm = $connexion->query('SELECT quantite FROM lignefraisforfait WHERE idVisiteur = "' .$id. '" AND idFraisForfait = "KM" AND mois = "' .$_SESSION['annee_mois']. '"');
          $fraisKm = $resultatFraisKm->fetch();

          $resultatNuitee = $connexion->query('SELECT quantite FROM lignefraisforfait WHERE idVisiteur = "' .$id. '" AND idFraisForfait = "NUI" AND mois = "' .$_SESSION['annee_mois']. '"');
          $nuitee = $resultatNuitee->fetch();

          $resultatRepas = $connexion->query('SELECT quantite FROM lignefraisforfait WHERE idVisiteur = "' .$id. '" AND idFraisForfait = "REP" AND mois = "' .$_SESSION['annee_mois']. '"');
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
        <?php
      }

      function recuperationElementsHorsForfait($connexion, $id){
        $resultatHorsForfait = $connexion->query('select libelle, date, montant from lignefraishorsforfait where idVisiteur = "' .$id. '" and mois = ' .$_SESSION['annee_mois']);
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
      }
      ?>
