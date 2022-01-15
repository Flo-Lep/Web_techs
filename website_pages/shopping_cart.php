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
    <title>WEBSITE-Panier</title>
    <link rel="stylesheet" type="text/css" href="../css/base.css">
    <link rel="stylesheet" type="text/css" href="../css/shopping_cart.css">
    <link href="https://fonts.googleapis.com/css2?family=Electrolize&display=swap" rel="stylesheet">
  </head>
  <body>
    <h1 class = "TITLE" id = "website_title">3D FAMILY</h1>
    <div class="MENU" id = "nav_bar">
      <a href="../index.php" class = "nav_bar_link">Accueil</a>
      <a href="game.php" class = "nav_bar_link">Jeu</a>
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
    <div class = "cart_logo">
      <a href="shopping_cart.php"><img src="../img/shopping_cart_logo.png" alt="" width = "30px"></a>
    </div>
<?php if($connected){
        create_shopping_cart();
        if(isset($_GET['delete_item'])){
          unset($_GET['delete_item']);
          delete_items_from_shopping_cart();
        }
?>
        <!--Affichage du panier-->
        <h1>VOTRE PANIER</h1><br>
        <?php if ($_SESSION['shopping_cart_state'] == 0){
          echo "Ajoutez des articles à votre panier pour passer commande !";
        }
        else if($_SESSION['shopping_cart_state'] == 1){?>
            <span>Détails de votre commande :</span><br><br>
      <?php for($i=0;$i<count($_SESSION['shopping_cart']['PRODUCT_ID']);$i++){?>
            <span class = "order_info">ID Produit : <?php echo $_SESSION['shopping_cart']['PRODUCT_ID'][$i]; ?></span>
            <span class = "order_info">Nom du produit : <?php echo $_SESSION['shopping_cart']['Name'][$i]; ?></span>
            <span class = "order_info">Qté : <?php echo $_SESSION['shopping_cart']['Qty'][$i]; ?></span>
            <span class = "order_info">Prix unitaire : <?php echo $_SESSION['shopping_cart']['Price'][$i]."€";?></span>
            <br>
      <?php }?>
            <form class="" action="#" method="GET"><input type="submit" name="delete_item" value="Vider le panier"></form><br>
            <br><br><span class = "order_info">Grand Total : <?php echo $_SESSION['shopping_cart']['Total_cost']."€"; ?></span>
            <form class="place_oder" action="order_placement.php" method="post"><br><input type="submit" name="place_order" value="VALIDER LA COMMANDE"></form>
      <?php }
      }else{ ?>
      <br><br><span id = "connection_msg">Veuillez vous connecter pour accéder aux fonctionnalités du site...</span>
    <?php } ?>

  </body>
  <footer>
		<img id="eseo_logo" src="../img/eseo_logo.png" width ="125">
    <p id="author">Made by Florentin LEPELTIER &copy;</p>
  </footer>
</html>
