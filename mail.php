<?php
require ('./include/connexion_bdd.php');

if ($_POST['check']=="07682153")
{
    $connexion->exec("insert into contact values ('', '".$_POST['nom']."', '".$_POST['prenom']."', '".$_POST['civilité']."', '".$_POST['email']."', '".$_POST['object']."', '".$_POST['message']."')");
    header('location:contact.php?message=Votre message a bien été envoyé ! En vous remerciant de porter un intéret pour GSB.');
}
else
    header('location:contact.php?message=Le captcha a mal été rempli. Veuillez réessayer.');


// if(isset($_POST['email'])){
//
// $destinataire = $_POST['email'];
// // Pour les champs $expediteur / $copie / $destinataire, séparer par une virgule s'il y a plusieurs adresses
// $expediteur = 's.gilbert2372@gmail.com';
// $copie = 'adresse@fai.com';
// $copie_cachee = 'adresse@fai.com';
// $objet = 'Test'; // Objet du message
// $headers  = 'MIME-Version: 1.0' . "\n"; // Version MIME
// $headers .= 'Content-type: text/html; charset=ISO-8859-1'."\n"; // l'en-tete Content-type pour le format HTML
// $headers .= 'Reply-To: '.$expediteur."\n"; // Mail de reponse
// $headers .= 'From: "Nom_de_expediteur"<'.$expediteur.'>'."\n"; // Expediteur
// $headers .= 'Delivered-to: '.$destinataire."\n"; // Destinataire
// $headers .= 'Cc: '.$copie."\n"; // Copie Cc
// $headers .= 'Bcc: '.$copie_cachee."\n\n"; // Copie cachée Bcc
// $message = '<div style="width: 100%; text-align: center; font-weight: bold">Un Bonjour de Developpez.com !</div>';
// if (mail($destinataire, $objet, $message, $headers)) // Envoi du message
// {
//     echo 'Votre message a bien été envoyé ';
// }
// else // Non envoyé
// {
//     echo "Votre message n'a pas pu être envoyé";
// }
// }
// ?>

<?php
        include('include/Pied_de_page.html');
        ?>
