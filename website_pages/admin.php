<?php
  include_once('../DB/functions_def.php');
  session_start();
  $connected = false;
  //Vérif si le gars est co
  if(isset($_SESSION['USER_ID'])){
    $connected = true;
  }
  //On vérifie que le gars a bien les droits d'admin
  (boolean)$admin = false;
  if($_SESSION['Status']==1){
    $admin = true;
  }

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>WEBSITE-Accueil</title>
    <link rel="stylesheet" type="text/css" href="../css/base.css">
    <link rel="stylesheet" type="text/css" href="../css/admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Electrolize&display=swap" rel="stylesheet">
  </head>
  <body>
    <h1 class = "TITLE" id = "website_title">3D FAMILY</h1>
    <div class="MENU" id = "nav_bar">
      <a href="../index.php" class = "nav_bar_link">Accueil</a>
      <a href="game.php" class = "nav_bar_link">Jeu</a>
      <a href="shop.php" class = "nav_bar_link">Magasin</a>
      <a href="profile.php" class = "nav_bar_link">Profil</a>
      <a href="#" class = "nav_bar_link">Admin</a>
      <?php if(!$connected){?>
        <a href="../DB/registration.php" class = "nav_bar_link">Inscription</a>
        <a href="../DB/authentification.php" class = "nav_bar_link">Connexion</a>
      <?php }else{?>
        <a href="../DB/log_out.php" class = "nav_bar_link">Deconnexion</a>
        <a href="profile.php" class = "nav_bar_link"><?php echo $_SESSION['First_name']." ".$_SESSION['Last_name']; ?></a>
      <?php } ?>
    </div>
    <div class = "cart_logo">
      <a href="shopping_cart.php"><img src="../img/shopping_cart_logo.png" alt=""></a>
    </div>
    <?php if($connected && $admin){
      //REQUETES SQL
      $response_1 = SQL_request($bdd,"Select Count(*) From USERS_TAB;");
      $response_2 = SQL_request($bdd,("Select Count(*) From INVOICES_TAB;"));
      //print_r($response_1);
      //$response_3 = "";
      ?>
      <div class="admin_content">
        <br><h1 id = "admin_TITLE">MODE ADMINISTRATEUR</h1>
        <span id = "admin_information">
          <h3 id = "admin_title">Statistiques du site</h3>
          Nombre de joueurs inscrits : <?php echo $response_1[0][0];?><br>
          Nombre de transactions : <?php echo $response_2[0][0];?><br>
          Record max au jeu :<br>
          <h3 id = "admin_title">Dictionnaire de données : </h3>
          <h3 id = "admin_title">MCD :</h3><br>
          <h3 id = "admin_title">MLD :</h3><br>
          <h3 id = "admin_title">Message du Webmaster :</h3><br>
          Cette partie traite du fonctionnement global des différents élements constituant le projet.<br><br>
          PARTIE AFFICHAGE HTML/CSS : A chaque page est associée un fichier.php ainsi que 2 feuilles de style. Une feuille de style "base.css" qui regroupe le css commun entre
          les différentes pages (barre de menus, panier, etc.). La seconde feuille de style permet de gérer l'affichage spécifique au contenu de chaque page.<br><br>
          PARTIE SQL : le fichier "functions_def.php" contient les definitions des fonctions permettant de communiquer avec la base de données, notamment la fonction "SQL_request()"
          qui permet d'effectuer des requetes SQL simplement.<br><br>
          PANIER : Le panier utilise les variables de sessions pour stocker temporairement les items. La definition des fonctions permettant de gérer le panier se trouve également
          dans le fichier "functions_def.php".<br><br>
          </span>
      <?php }else{ ?>
        <br><br><span id = "connection_msg">Veuillez vous connecter pour accéder aux fonctionnalités du site...Si vous l'êtes déjà,
           vous n'avez pas accès au mode administrateur</span>
      <?php } ?>
      </div>
  </body>
  <footer>
		<img id="eseo_logo" src="../img/eseo_logo.png" width ="125">
    <p id="author">Made by Florentin LEPELTIER &copy;</p>
  </footer>
</html>
