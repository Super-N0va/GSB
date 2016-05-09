<!DOCTYPE html>
<html>
    <header>
        <meta charset="utf-8">
        <link rel="stylesheet" href="./styles/design.css" />

    </header>
    <body>

        <?php

        include('include/entete.html');
        ?>

        </br>
        </br>
        <h2 id="titre">
            </br><div class="titre"><u><h1>Page de connexion</h1></u></div>
           </h2>

        </br>
        <form method="post" action="connexion.php">
     <?php
        if (isset($_GET['message']))
           {
            echo $_GET['message'];
           }
       ?>
        <center>Votre identifiant : <input type="text" name="login" class="connexion" size="30"
               placeholder="Taper votre login" maxlength="30"
               autofocus="autofocus" required=""/></center>
    </br>
        <center>Votre mot de passe : <input type="password" name="password" class="connexion" size="30"
               placeholder="Taper votre mot de passe" maxlength="50"
               autofocus="autofocus" required=""/></center>
    </br>
    </br>
    <center><input type="submit" value="Valider"/>&nbsp;&nbsp;&nbsp;
        <input type="reset" value="Effacer" /></center>
        </form>

        <?php
        include('include/pied_de_page.html');
        ?>
    </body>

</html>
