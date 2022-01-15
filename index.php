<?php
  include_once('DB/functions_def.php');
  session_start();
  $connected = false;
  //Vérif si le gars est co
  if(isset($_SESSION['USER_ID'])){
    $connected = true;
  }
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>WEBSITE-Accueil</title>
    <link rel="stylesheet" type="text/css" href="css/base.css"/>
    <link rel="stylesheet" type="text/css" href="css/home.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Electrolize&display=swap" rel="stylesheet">
    <script type="text/javascript" src = "js/index.js"></script>
  </head>
  <body>
    <h1 class = "TITLE" id = "website_title">3D FAMILY</h1>
    <div class="MENU" id = "nav_bar">
      <a href="#" class = "nav_bar_link">Accueil</a>
      <a href="website_pages/game.php" class = "nav_bar_link">Jeu</a>
      <a href="website_pages/shop.php" class = "nav_bar_link">Magasin</a>
      <a href="website_pages/profile.php" class = "nav_bar_link">Profil</a>
      <a href="website_pages/admin.php" class = "nav_bar_link">Admin</a>
      <?php if(!$connected){?>
        <a href="DB/registration.php" class = "nav_bar_link">Inscription</a>
			  <a href="DB/authentification.php" class = "nav_bar_link">Connexion</a>
      <?php }else{?>
        <a href="DB/log_out.php" class = "nav_bar_link">Deconnexion</a>
        <a href="website_pages/profile.php" class = "nav_bar_link"><?php echo $_SESSION['First_name']." ".$_SESSION['Last_name']; ?></a>
      <?php } ?>
    </div>
    <div class = "cart_logo">
      <a href="website_pages/shopping_cart.php"><img src="img/shopping_cart_logo.png" alt=""></a>
    </div>
    <?php if($connected){ ?>
      <?php //ON VERIFIE SI L'UTILISATEUR A VERIFIE SON COMPTE
        $checking = SQL_request($bdd,"Select * From USERS_TAB Where USER_ID = ".(int)$_SESSION['USER_ID'].";");
        if($checking[0]['Checking']==0){echo"Vous n'avez pas encore vérifié votre compte, faites-le en 1 clic !<br>";?>
          <form class="" action="#" method="post">
            <input type="submit" name="checking" value="VERIFIER MON COMPTE">
          </form>
      <?php } ?>
      <?php if(isset($_POST['checking'])){
            simple_SQL_request($bdd,"Update USERS_TAB Set Checking = 1 Where USER_ID = ".$_SESSION['USER_ID'].";",$checking);
            echo "Félicitations, votre compte a bien été vérifié !";
            unset($_POST['checking']);
      } ?>
      <div class="home_content">
        <span id="home_msg">Bienvenue <?php echo $_SESSION["First_name"]." !";?> </span><br><br>
        <!--Annonce d'accueil-->
        <span id = "home_text_title">Le saviez-vous ?</span><br><br>
        <span id = "home_text_content">Vous pensez que l’impression 3D est récente ? Détrompez vous ! L’histoire de l’impression 3D est riche et complexe. En 2009, lorsque les brevets de la FDM ont expiré, l’impression 3D est devenue un sujet tellement en vogue qu’il était facile de penser à une toute nouvelle innovation. Et au vu de cette couverture médiatique massive, les gens ont souvent imaginé que la FDM était l’unique méthode de fabrication additive.
          En réalité, la première méthode d’impression 3D était la Stéréolithographie (SLA), pas la FDM, et son premier brevet avait déjà été déposé durant les années 80</span><br><br>
        <!--Infos complémentaires-->
        <span id = "infos_1">Qui sommes-nous ?</span><br><br>
        <span id = "infos_2">La 3D FAMILY dispose de plus d'une dizaine d'années d'expérience dans l'impression 3D.
          Notre personnel qualifié est là pour répondre à toutes vos interrogations et vous accompagne de l'emergence de votre projet
         à sa finalisation !</span><br><br>
        <!--Formulaire de contact-->
        <br><span class = "contact">Nous contacter</span>
        <form class="contact_form" action="#" method="post">
          <input type="text" name="Email" placeholder = "E-mail" required><br><br>
          <textarea name="Message" rows="8" cols="80" placeholder="Votre message" required></textarea>
          <input type="submit" name="Contact" value="ENVOYER">
        </form>
      </div>

    <?php }else{ ?>
      <!--<span class = "connection_msg" >Veuillez vous connecter pour accéder aux fonctionnalités du site...</span>-->
      <br><br><div id = "connection_msg">Veuillez vous connecter pour accéder aux fonctionnalités du site...</div>
    <?php } ?>

  </body>

  <footer>
		<img id="eseo_logo" src="img/eseo_logo.png" width ="125">
    <p id="author">Made by Florentin LEPELTIER &copy;</p>
  </footer>
</html>
