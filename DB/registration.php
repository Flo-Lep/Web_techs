<?php
  include_once('functions_def.php');
  session_start();
  $connected = false;
  //Vérif si le gars est co
  if(isset($_SESSION['USER_ID'])){
    $connected = true;
  }
  $feedback = null;
  $last_name_err ="";$first_name_err="";$e_mail_err="";$password_err="";$country_err="";$address_err="";$phone_err="";
  $valid = (boolean) true;

  if(!empty($_POST)){ //Si le tableau est vide c'est qu'aucun formulaire n'a été transmis
   extract($_POST);
   if(isset($_POST['INSCRIPTION'])){
     $Last_name = (String) trim($Last_name); //On retire les espaces inutiles et on évite
     $First_name = (String) trim($First_name);//les erreurs...
     $Email = (String) trim($Email);
     $Password = (String) trim($Password);$Password_ = password_hash($Password,PASSWORD_DEFAULT); //sha1
     $Country = (String) trim($Country);
     $Address = (String) trim($Address);
     $Phone = (String) trim($Phone);
     //Vérification de la saise des données
     if(empty($Last_name)){$valid = false;$last_name_err = "Ce champ est obligatoire";}
     if(empty($First_name)){$valid = false;$first_name_err = "Ce champ est obligatoire";}
     if((empty($Email)) || (!filter_var($Email, FILTER_VALIDATE_EMAIL))){$valid = false;$e_mail_err = "Entrez une adresse mail valide";}
     if(empty($Password)){$valid = false;$password_err = "Ce champ est obligatoire";}
     if(empty($Country)){$valid = false;$country_err = "Ce champ est obligatoire";}
     if(empty($Address)){$valid = false;$address_err = "Ce champ est obligatoire";}
     if(empty($Phone)){$valid = false;$phone_err = "Ce champ est obligatoire";}
     //On vérifie que l'adresse Email n'est pas déjà associée à un compte
     $user_emails = SQL_request($bdd,"Select * From USERS_TAB Where Email = '".$Email."';"); if($user_emails!=null){$valid = false;}
     //Si tous les champs sont conformes, on envoie la requête SQL appropriée
     if($valid){
       $req = $bdd->prepare("Insert Into USERS_TAB (Last_name,First_name,Email,Password_,Country,Address,Phone)
        VALUES(?, ?, ?, ?, ?, ?, ?);");
       $req->execute(array($Last_name,$First_name,$Email,$Password_,$Country,$Address,$Phone));
       $feedback ="Votre inscription a bien été prise en compte, vérifiez votre compte via votre adresse email
       pour finaliser votre inscription !";
     }
     else{
       $feedback = "Erreur lors de l'enregistrement de votre inscription, cette adresse email est déjà associée à un compte utilisateur...";
     }
   }
  }
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>WEBSITE-Inscription</title>
    <link rel="stylesheet" type="text/css" href="../css/base.css">
    <link rel="stylesheet" type="text/css" href="../css/authentification.css">
    <link href="https://fonts.googleapis.com/css2?family=Electrolize&display=swap" rel="stylesheet">
  </head>
  <body>
    <!--BARRE DE MENU-->
    <h1 class = "TITLE" id = "website_title">3D FAMILY</h1>
    <div class="MENU" id = "nav_bar">
      <a href="../index.php" class = "nav_bar_link">Accueil</a>
      <a href="../website_pages/game.php" class = "nav_bar_link">Jeux</a>
      <a href="../website_pages/shop.php" class = "nav_bar_link">Magasin</a>
      <a href="../website_pages/profile.php" class = "nav_bar_link">Profil</a>
      <a href="../website_pages/admin.php" class = "nav_bar_link">Admin</a>
      <a href="#" class = "nav_bar_link">Inscription</a>
      <?php if(!$connected){?>
			  <a href="authentification.php" class = "nav_bar_link">Connexion</a>
      <?php }else{?>
        <a href="log_out.php" class = "nav_bar_link">Deconnexion</a>
      <?php } ?>
    </div>
    <!--FORMULAIRE D'INSCRIPTION-->
    <h1 class = "auth_title">INSCRIPTION</h1>
    <div class="form">
      <form method="POST">
        <input type="text" name="Last_name" placeholder = "Nom" required>
        <?php if(!$valid){echo $last_name_err;}?>
        <br><br>
        <input type="text" name="First_name" placeholder= "Prenom" required>
        <?php if(!$valid){echo $first_name_err;}?>
        <br><br>
        <input type="text" name="Email" placeholder = "E-mail" required>
        <?php if(!$valid){echo $e_mail_err;}?>
        <br><br>
        <input type="password" name="Password" placeholder = "Mot de passe" required>
        <?php if(!$valid){echo $password_err;}?>
        <br><br>
        <input type="text" name="Country" placeholder = "Pays" required>
        <?php if(!$valid){echo $country_err;}?>
        <br><br>
        <input type="text" name="Address" placeholder = "Adresse" required>
        <?php if(!$valid){echo $address_err;}?>
        <br><br>
        <input type="text" name="Phone" placeholder = "Téléphone" required>
        <?php if(!$valid){echo $phone_err;}?>
        <br><br>
        <input type="submit" name="INSCRIPTION" value="INSCRIPTION">
        <br><br>
      </form>
    </div>
    <?php if($feedback){echo $feedback;}?>
    </div>

  </body>

  <footer>
		<img id="eseo_logo" src="../mg/eseo_logo.png" width ="125">
    <p id="author">Made by Florentin LEPELTIER &copy;</p>
  </footer>
</html>
