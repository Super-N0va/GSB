<?php

/**
* Fonction qui verifie si la connexion d'un utilisateur est bien active.
*/
function checkConnection() {
  if(!isset($_SESSION['nom']) && !isset($_SESSION['prenom'])){
    header('Location: index.php?message=Veuillez vous connecter pour pouvoir accéder à cette page');
  }
  echo "<h2 class='titre'>".$_SESSION['nom']." ".$_SESSION['prenom']."</h2>";
}

/**
* Fonction qui affiche les différents mois dans une liste déroulante.
* @param type $connexion -> variable de connexion à la base de données.
*/
function listeMois($connexion){
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

/**
* Fonction qui permet de connaître l'état d'une fiche de frais.
* @param type $connexion -> variable de connexion à la base de données.
* @param type $mois -> variable récupérant le mois concerné.
* @param type $id -> variable récupérant l'id de l'utilisateur connecté.
*/
function etatFiche($connexion, $mois, $id) {
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

  /**
  * Fonction qui affiche le montant total d'une fiche de frais.
  * @param type $connexion -> variable de connexion à la base de données.
  * @param type $id -> variable récupérant l'id de l'utilisateur connecté.
  */
  function montantFiche($connexion, $id) {
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

    /**
    * Fonction qui affiche la quantité d'éléments forfaitisés.
    * @param type $connexion  -> variable de connexion à la base de données.
    * @param type $id -> variable récupérant l'id de l'utilisateur connecté.
    */
    function ligneFraisForfait($connexion, $id) {
      $resultat2 = $connexion->query('SELECT quantite
        FROM lignefraisforfait
        WHERE idVisiteur="'.$id.'"
        AND mois = "'.$_POST['mois'].'"');
        while($ligne = $resultat2->fetch())
        {
          $idfrais = $ligne['quantite'];
          echo  "<td width='25%' align='center'>".$idfrais."</td>";
        }
        $resultat2->closeCursor();
      }

      /**
      * Fonction qui affiche les éléments hors forfait d'une fiche de frais.
      * @param type $connexion  -> variable de connexion à la base de données.
      * @param type $id -> variable récupérant l'id de l'utilisateur connecté.
      */
      function ligneFraisHorsForfait($connexion, $id) {
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

        /**
        * Fonction qui permet de créer une nouvelle fiche de frais.
        * @param type $connexion  -> variable de connexion à la base de données.
        * @param type $id -> variable récupérant l'id de l'utilisateur connecté.
        */
        function creationFicheFrais($connexion, $id){
          $jour = date("d");
          $annee = date('Y');
          $mois = date('m');

          $_SESSION['annee_mois'] = $annee.$mois;
          $sql = "SELECT * FROM fichefrais WHERE idVisiteur = '" .$id."' and mois='".$_SESSION['annee_mois']."'";
          $resultat = $connexion->query($sql);
          if (!$ligne = $resultat->fetch())
          {
            $date = date('YYYY-MM-DD');
            $connexion->exec("INSERT INTO fichefrais VALUES ('".$id."', '".$_SESSION['annee_mois']."', 0, 0, $date, 'CR')");
            $connexion->exec("INSERT INTO lignefraisforfait SELECT '".$id."', '".$_SESSION['annee_mois']."', id, 0 from fraisforfait");
          }
        }

        /**
        * Fonction qui permet d'afficher la quantité de chaque éléments constituant une fiche de fras du mois et de l'année actuelle.
        * @param type $connexion  -> variable de connexion à la base de données.
        * @param type $id -> variable récupérant l'id de l'utilisateur connecté.
        */
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

      /**
      * Fonction qui permet d'afficher les éléments hors forfait du mois et de l'année actuelle.
      * @param type $connexion  -> variable de connexion à la base de données.
      * @param type $id -> variable récupérant l'id de l'utilisateur connecté.
      */
      function recuperationElementsHorsForfait($connexion, $id){
        $resultatHorsForfait = $connexion->query('SELECT libelle, date, montant FROM lignefraishorsforfait WHERE idVisiteur = "' .$id. '" and mois = ' .$_SESSION['annee_mois']);
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

      /**
      * Fonction qui permet de vérifier si le captcha du formulaire de contact a bien été rempli.
      * @param type $connexion  -> variable de connexion à la base de données.
      */
      function checkForm($connexion){
        if ($_POST['check']=="07682153")
        {
          $fichier = $_FILES['fichier'];
          insertMsgBDD($connexion, $fichier);
        }
        else {
          header('location:contact.php?message=Le captcha a mal été rempli. Veuillez réessayer.');
        }
      }


      /**
      * Fonction qui permet d'envoyer un email automatique après validation du formulaire.
      * @param type $to  -> variable contenant l'email de destination rentrée par l'utlisateur.
      * @param type $prenom -> variable récupérant le prénom de l'utilisateur concerné.
      */
      function envoiMail($to, $prenom) {
        $subject = 'Prise en compte de votre demande';
        $message =

        'Bonjour ' .$prenom.' !

        Je vous informe que votre demande a été prise en compte.
        Vous recevrez bientôt une réponse de notre part, veuillez ne pas répondre à cette adresse automatique.

        Cordialement, l\'équipe GSB';
        $headers = 'From: no-reply@gsb.com' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();
        mail($to, $subject, $message, $headers);
      }

      /**
      * Fonction qui permet d'ajouter un fichier dans le dossier received_files en vérifiant sa taille et son extension.
      * Elle ajoute également les différentes coordonnées dans la base de données.
      * @param type $connexion  -> variable de connexion à la base de données.
      * @param type $file -> variable récupérant le fichier téléversé par l'utilisateur.
      */
      function insertMsgBDD($connexion, $file){
        if(isset($file)){
          $to = $_POST['email'];
          $prenom = $_POST['prenom'];

          $dossier = './received_files/';
          $fichier = basename($file['name']);
          $taille_maxi = 1048576;
          $taille = filesize($file['tmp_name']);
          $extensions = array('.pdf', '.doc', '.odt', '.docx', '');
          $extension = strrchr($file['name'], '.');
          //Début des vérifications de sécurité...
          if(!in_array($extension, $extensions)) //Si l'extension n'est pas dans le tableau
          {
            $erreur = 'L\'extension de ce fichier n\'est pas autorisée, veuillez ajouter un fichier correct (pdf, doc, odt, docx)';

          }
          if($taille>$taille_maxi)
          {
            $erreur = 'Le fichier est trop lourd, la limite étant à 1Mo';

          }
          if(!isset($erreur)) //S'il n'y a pas d'erreur, on upload
          {
            //On formate le nom du fichier ici...
            $fichier = strtr($fichier,
            'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ',
            'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
            $fichier = preg_replace('/([^.a-z0-9]+)/i', '-', $fichier);

            if(move_uploaded_file($file['tmp_name'], $dossier . $fichier)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
            {
              envoiMail($to, $prenom);
              $connexion->exec("INSERT INTO contact VALUES ('', '".$_POST['nom']."', '".$_POST['prenom']."', '".$_POST['civilité']."', '".$_POST['email']."', '".$_POST['object']."', '".$_POST['message']."', '".$fichier."')");
              header('location:contact.php?message=Votre message a bien été envoyé ! En vous remerciant de porter un intéret pour GSB.');
            } else {
              envoiMail($to, $prenom);
              $connexion->exec("INSERT INTO contact VALUES ('', '".$_POST['nom']."', '".$_POST['prenom']."', '".$_POST['civilité']."', '".$_POST['email']."', '".$_POST['object']."', '".$_POST['message']."', '')");
              header('location:contact.php?message=Votre message a bien été envoyé ! En vous remerciant de porter un intéret pour GSB.');
            }

          }
          else
          {
            header('location:contact.php?message='.$erreur.'');
          }
        }

      }

      ?>
