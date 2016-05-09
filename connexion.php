<?php
     session_start();
     require ('./include/connexion_bdd.php');

        $sql=$connexion->query("SELECT * FROM visiteur WHERE login = '".$_POST['login']."'AND mdp = '".$_POST['password']."'");
        $req = $sql->fetch();
        if($req['login'] != NULL)
        {
            $_SESSION['pseudo'] = $req['login'];
            $_SESSION['nom'] = $req['nom'];
            $_SESSION['prenom'] = $req['prenom'];
            $_SESSION['id']= $req['id'];
            header("location:espacePerso.php");
        }

        else
            header("location:authentification.php?message=Erreur ! Veuillez ressaisir votre login ou votre mot de passe");
?>
