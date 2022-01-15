<?php
  include_once('../DB/functions_def.php');
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
    <title>WEBSITE-JEU</title>
    <link rel="stylesheet" type="text/css" href="../css/base.css">
    <link rel="stylesheet" type="text/css" href="../css/game.css">
    <link href="https://fonts.googleapis.com/css2?family=Electrolize&display=swap" rel="stylesheet">
  </head>
  <body>
    <h1 class = "TITLE" id = "website_title">3D FAMILY</h1>
    <div class="MENU" id = "nav_bar">
      <a href="../index.php" onmouseover="" class = "nav_bar_link">Accueil</a>
      <a href="#" class = "nav_bar_link">Jeu</a>
      <a href="shop.php" class = "nav_bar_link">Magasin</a>
      <a href="profile.php" class = "nav_bar_link">Profil</a>
      <a href="admin.php" class = "nav_bar_link">Admin</a>
      <?php if(!$connected){?>
        <a href="../DB/registration.php" class = "nav_bar_link">Inscription</a>
        <a href="../DB/authentification.php" class = "nav_bar_link">Connexion</a>
      <?php }else{?>
        <a href="../DB/log_out.php" class = "nav_bar_link">Deconnexion</a>
        <a href="profile.php" class = "nav_bar_link"><?php echo $_SESSION['First_name']." ".$_SESSION['Last_name']; ?></a>
      <?php } ?>
    </div>
    <?php if($connected){ ?>
      <h1 id = "game_TITLE">JEU</h1>
      <canvas id = "canvas" width = "600" height="600"></canvas>
      <script type="text/javascript" src="../js/js_game.js"></script>
      <script>
        //On lance le jeu
        <?php if(isset($_POST['OK'])){
          echo "printer.change_filament_color("."'".$_POST["color"]."'".");";
          unset($_POST['OK']);
        } ?>
        launch_game();
      </script>
      <form class="" action="#" method="post">
        <label for="color">Couleur du filament</label>
        <select class="" name="color">
          <option value="blue">BLEU</option>
          <option value="red">ROUGE</option>
          <option value="green">VERT</option>
          <option value="yellow">JAUNE</option>
          <option value="purple">VIOLET</option>
          <input type="submit" name="OK" value="OK">
        </select>
      </form>
      <h2>Règles du jeu :</h2>
      <span>Vous commandez la tête d'impression de votre imprimante 3D. Guidez là avec les flèches de votre clavier. Pressez une fois la barre d'espace
      pour activer ou non l'extrudeur et faire sortir le filament. Le but est d'imprimer la forme représentée en vert, pour cela il faut la recouvrir entièrement de filament.
      Plus vous serrez rapide, plus vous gagnerez de points !</span><br>
      <h2>Meilleurs scores des joueurs</h2>
      <?php $score = SQL_request($bdd,"Select * From GAME_TAB ORDER BY Score DESC;");
        for($k=0;$k<count($score);$k++){
          $user = SQL_request($bdd,"Select * From USERS_TAB Where USER_ID = ".$score[$k]['USER_ID'].";");
          echo "".$user[0]['First_name']." ".$user[0]['Last_name']." : ".$score[$k]['SCORE']."<br><br>";
        }
       ?>
      <h2>Laissez-nous votre avis !</h2>
      <form class="" action="#" method="post">
        <textarea name="comment" rows="8" cols="80" requested placeholder="Votre commentaire"></textarea><br>
        <input type="submit" name="game_comment" value="POSTER">
      </form>
      <?php if(isset($_POST['game_comment'])){
        simple_SQL_request($bdd,"Insert Into GAME_TAB (SCORE,USER_ID,G_comment) Values(0,".(int)$_SESSION['USER_ID'].",'".$_POST['comment']."');",$connected); //Le $connected sert à rien
        unset($_POST['game_comment']);
      } ?>
      <h2>Commentaires utilisateurs</h2>
      <?php
        $comments = SQL_request($bdd,"Select * From GAME_TAB;");
        for($s=0;$s<count($comments);$s++){
          $user_ = SQL_request($bdd,"Select * From USERS_TAB Where USER_ID = ".$comments[$s]['USER_ID'].";");
          echo "".$user_[0]['First_name']." ".$user_[0]['Last_name']." :<br>".$comments[$s]['G_comment']."<br><br>";
        }
      ?>
    <?php }else{ ?>
      <br><br><span id = "connection_msg">Veuillez vous connecter pour accéder aux fonctionnalités du site...</span>
    <?php } ?>

  </body>
  <footer>
		<img id="eseo_logo" src="../img/eseo_logo.png" width ="125">
    <p id="author">Made by Florentin LEPELTIER &copy;</p>
  </footer>
</html>
