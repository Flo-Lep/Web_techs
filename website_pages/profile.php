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
    <title>WEBSITE-Accueil</title>
    <link rel="stylesheet" href="../css/base.css" type="text/css" />
    <link rel="stylesheet" href="../css/profile.css" type="text/css" />
    <link href="https://fonts.googleapis.com/css2?family=Electrolize&display=swap" rel="stylesheet">
  </head>
  <body>
    <!--BARRE DE MENU-->
    <h1 class = "TITLE" id = "website_title">3D FAMILY</h1>
    <div class="MENU" id = "nav_bar">
      <a href="../index.php" class = "nav_bar_link">Accueil</a>
      <a href="game.php" class = "nav_bar_link">Jeu</a>
      <a href="shop.php" class = "nav_bar_link">Magasin</a>
      <a href="#" class = "nav_bar_link">Profil</a>
      <a href="admin.php" class = "nav_bar_link">Admin</a>
      <?php if(!$connected){?>
        <a href="../DB/registration.php" class = "nav_bar_link">Inscription</a>
        <a href="../DB/authentification.php" class = "nav_bar_link">Connexion</a>
      <?php }else{?>
        <a href="../DB/log_out.php" class = "nav_bar_link">Deconnexion</a>
        <a href="#" class = "nav_bar_link"><?php echo $_SESSION['First_name']." ".$_SESSION['Last_name']; ?></a>
      <?php } ?>
    </div>
    <div class = "cart_logo">
      <a href="shopping_cart.php"><img src="../img/shopping_cart_logo.png" alt=""></a>
    </div>
    <?php if($connected){
      //REQUETE SQL
      $id = $_SESSION['Email'];
      $pwd = $_SESSION['Password_'];
      $req = $bdd->prepare('Select * From USERS_TAB Where Email = ? And Password_ = ?;');
      $req->execute(array($id,$pwd));
      $result = $req->fetch();
      //Commandes
      $invoices = SQL_request($bdd,"Select * From INVOICES_TAB Where USER_ID =".$_SESSION['USER_ID'].";");
      ?>
      <br>
      <div class="profile_content">
        <h3 id = "profile_title">Votre profil</h3>
        <span id = "profile_information">
          Nom : <?php echo $result['Last_name'];?>
          <br>
          Prenom : <?php echo $result['First_name'];?>
          <br>
          Membre confirmé : <?php if(($result['Checking'])==1){echo "OUI";}else{echo "NON";};?>
          <br>
          Date d'inscription : <?php echo $result['Registration_date'];?>
          <br>
          Pays : <?php echo $result['Country'];?>
          <br>
          <h3 id = "profile_title">Informations privées</h3>
          E-mail : <?php echo $result['Email'];?>
          <br>
          Adresse : <?php echo $result['Address'];?>
          <br>
          Téléphone : <?php echo $result['Phone'];?>
          <br>
          Liste des commandes :
          <br>
          Solde : <?php echo $result['Balance']."€";?>
          <br>
          Record max au jeu :
          <br><br>
          *Veuillez noter que vos informations privées ne sont en aucun cas visibles par les autres utilisateurs<br>
          (Vous ne pourrez pas passer commande si vous n'êtes pas un membre confirmé...)
          <h3 id = "profile_title">Vos commandes</h3>
          <?php if($invoices == null){echo"Vous n'avez pas encore passé de commandes. Rendez-vous dans la boutique pour
            faire vos premiers achats !";} ?>
          <?php for($i=0;$i<count($invoices);$i++){
            echo "Commande n°".$invoices[$i]['INVOICE_ID']." --> ";
            echo "Montant TOTAL : ".$invoices[$i]['Total_cost']."€ - Date : ".$invoices[$i]['Date'];
            $order = SQL_request($bdd,"Select * From ORDERS_TAB Where INVOICE_ID = ".$invoices[$i]['INVOICE_ID'].";)");
            echo "<br> Produits :<br>";
            for($z=0;$z<count($order);$z++){
              $products = SQL_request($bdd,"Select * From PRODUCTS_TAB Where PRODUCT_ID = ".(int)$order[$z]['PRODUCT_ID'].";)");
              for($x=0;$x<count($products);$x++){
                echo "-".$products[$x]['Name']." (x".$order[$z]['Qty'].")<br>";
              }
            }
            echo "<br><br>";
          } ?>
          <br>
          </span>
        <?php }else{ ?>
          <br><br><span id = "connection_msg">Veuillez vous connecter pour accéder aux fonctionnalités du site...</span>
        <?php } ?>
      </div>

  </body>
  <footer>
		<img id="eseo_logo" src="../img/eseo_logo.png" width ="125">
    <p id="author">Made by Florentin LEPELTIER &copy;</p>
  </footer>
</html>
