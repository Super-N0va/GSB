<!DOCTYPE html>
<html>
<header>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <link rel="stylesheet" href="./styles/design.css" />
</header>

<body>
  <?php
  require ('include/entete.html');
  ?>
</br>
</br>


<u></br><div class="titre"><h1>Contact GSB</h1></br></div></u>

<br>
<title>Contact GSB</title>
<?php
if (isset($_GET['message']))
echo $_GET['message'];
?>
<br>
<br>
<form action="mail.php" method="post" enctype="multipart/form-data">
  <fieldset>
    <font color="red">* : champs obligatoires</font>
    <br>
    <br>
    <legend>Civilité</legend>
    <label for="man">Sexe* :</label>
    <input type="radio" name="civilité" id="man" value="Homme" required>

    <label for="man">Homme</label>
    <input type="radio" name="civilité" id="women" value="Femme" required>
    <label for="women">Femme</label>
    <br>
    <br>
    <label for="name">Nom* :</label>
    <input id="name" type="text" name="nom" maxlenght="20" required=""/>&nbsp;&nbsp;&nbsp;
    <label for="name2">Prénom* :</label>
    <input id="name2" type="text" name="prenom" maxlenght="20" required=""/>&nbsp;&nbsp;&nbsp;
    <label for="email">Email* :</label>
    <input id="email" type="email" name="email" required="@"/>
  </fieldset>

  <br>

  <fieldset>
    <legend>Objet du message</legend>
    <br>
    <label for="object">Sujet du message* :</label>
    <select name="object">
      <option value="Remarque">Remarque</option>
      <option value="Question">Question</option>
      <option value="Autre probleme">Autre problème</option>
    </select>
    <br>
    <br>
    <textarea id="message" rows="10" name="message" required="" minlenght="20"></textarea>
    <br>
    <br>
    <label for="file">Pièce jointe 1Mo max (doc, docx, odt, pdf) :</label>
    <input type="file" name="fichier"/><br />
    <br>
    <img src="image/captcha.png">
    <input id="check" type="text" name="check" maxlenght="8">
  </fieldset>
  <br>

  <input type="submit" value="Valider"/>&nbsp;&nbsp;
  <input type="reset" value="Réinitialiser"/>

</form>
</body>
<?php
require ('include/pied_de_page.html');
?>
</html>
