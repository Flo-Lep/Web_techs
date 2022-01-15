<?php
include_once("../DB/functions_def.php");
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
    <title>WEBSITE-Produit</title>
    <link rel="stylesheet" type="text/css" href="../css/base.css"/>
    <link rel="stylesheet" type="text/css" href="../css/product.css"/>
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
      <a href="shopping_cart.php"><img src="../img/shopping_cart_logo.png" alt=""></a>
    </div>
    <?php if($connected){
      //Il faut récupérer le produit sur lequel l'utilisateur a cliqué
      $characters = "\\%\2\7\0\\'";
      $product = (String) trim($_GET['id'],$characters); //On retire les caractères parasites (%,2,7,0)
      //var_dump($product);
      $stringRequest = "Select * From PRODUCTS_TAB Where Name = '".$product."';";
      $response = SQL_request($bdd,$stringRequest);
      //Si l'utilisateur vient de cliquer sur "Ajouter au panier"
      if(isset($_POST['add_to_cart'])){
        $quantity = $_POST['Qty'];
        create_shopping_cart();
        add_item_to_shopping_cart($response,$quantity);
      }
    ?>
    <div class="buttons">
      <form class="add_to_cart" action="#" method="post">
        <label for="Qty">Quantité :</label>
        <select class="quantity" name="Qty">
          <option value="1">1</option>
          <option value="2">2</option>
          <option value="3">3</option>
          <option value="4">4</option>
          <option value="5">5</option>
          <option value="6">6</option>
          <option value="7">7</option>
          <option value="8">8</option>
          <option value="9">9</option>
          <option value="10">10</option>
        </select>
        <input type="submit" name="add_to_cart" value="Ajouter au panier">
      </form>
    </div>
    <div class="product_view">
      <img src=<?php echo "'".$response[0]['Location']."'";?> alt="">
      <div class="element_header">
        <h4 class = "title" id = "Product_name"><?php echo $response[0]['Name'];?></h4>
        <h4 class = "price"><?php echo $response[0]['Price']."€";?></h4>
        <h4 class = "category"><?php echo $response[0]['Category'];?></h4>
      <div class="element_body">
        <h1>Description :</h1>
        <p><?php echo $response[0]['Description'];?></p>
      </div>
      </div>
    </div>
    <div class="product_comment">
      <span>Laissez nous votre avis !</span><br><br>
      <form class="" action="#" method="post">
        <label for="grade">VOTRE NOTE :</label>
        <select class="grade" name="grade" required>
          <option value="1">1</option>
          <option value="2">2</option>
          <option value="3">3</option>
          <option value="4">4</option>
          <option value="5">5</option>
        </select><br>
        <textarea name="comment" rows="8" cols="80" placeholder="Votre commentaire"></textarea>
        <br><input type="submit" name="comment_submit" value="LAISSER UN AVIS">
      </form><br>
    </div>
    <?php
    if(isset($_POST['comment_submit'])){
      simple_SQL_request($bdd,"Insert into COMMENTS_TAB (USER_ID,PRODUCT_ID,Grade,Comment) VALUES(".$_SESSION['USER_ID'].",".$response[0]['PRODUCT_ID'].",".$_POST['grade'].",'".$_POST['comment']."');",$response);
      echo "Merci, votre avis a bien été transmis !";
    }
    ?>
    <div class="product_review">
      <span class = "product_review_title">Avis utilisateurs :</span><br><br>
      <?php //REQUETE AVIS
        $avis = SQL_request($bdd,"Select * From COMMENTS_TAB Where PRODUCT_ID = ".(int)$response[0]['PRODUCT_ID'].";");
        for($i=0;$i<count($avis);$i++){
          $nom = SQL_request($bdd,"Select * From USERS_TAB Where USER_ID = ".$avis[$i]['USER_ID'].";");
          echo $nom[0]['First_name']." ".$nom[0]['Last_name']." -> ";
          echo $avis[$i]['Comment'];
          echo " - Note : ";for($t=0;$t<$avis[$i]['Grade'];$t++){echo "&#x2605";}echo"(".$avis[$i]['Grade'].")";
          echo "<br><br>";
        }
        if($avis==null){echo"Aucun avis pour ce produit";}
      ?>
    </div>

    <?php }else{ ?>
      <br><br><div id = "connection_msg">Veuillez vous connecter pour accéder aux fonctionnalités du site...</div>
    <?php } ?>

  </body>

  <footer>
		<img id="eseo_logo" src="../img/eseo_logo.png" width ="125">
    <p id="author">Made by Florentin LEPELTIER &copy;</p>
  </footer>
</html>
