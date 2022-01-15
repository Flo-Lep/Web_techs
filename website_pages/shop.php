<?php
  //include_once('../DB/db_connection.php');
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
    <link rel="stylesheet" type="text/css" href="../css/base.css">
    <link rel="stylesheet" type="text/css" href="../css/shop.css">
    <link href="https://fonts.googleapis.com/css2?family=Electrolize&display=swap" rel="stylesheet">
  </head>
  <body>
    <h1 class = "TITLE" id = "website_title">3D FAMILY</h1>
    <div class="MENU" id = "nav_bar">
      <a href="../index.php" class = "nav_bar_link">Accueil</a>
      <a href="game.php" class = "nav_bar_link">Jeu</a>
      <a href="#" class = "nav_bar_link">Magasin</a>
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
    <?php if($connected){?>
      <!--BOUTIQUE-->
      <br>
      <div class="sorting_button" style = "position:absolute;right:80px;">
        <form class="sorting_form" action="#" method="GET">
          <select class="select_button" id = "sorting_button" name="sorting_select">
            <option value="DEFAULT" >TRIER PAR (DEFAUT)</option>
            <option value="CROISSANT">PRIX CROISSANT</option>
            <option value="DECROISSANT">PRIX DECROISSANT</option>
            <option value="CATEGORY_CROISSANT">CATEGORIE CROISSANTE</option>
            <option value="CATEGORY_DECROISSANT">CATEGORIE DECROISSANTE</option>
            <option value="POP_CROISSANT">POPULARITE CROISSANTE</option>
            <option value="POP_DECROISSANT">POPULARITE DECROISSANTE</option>
            <option value="DISPO_CROISSANT">DISPO CROISSANT</option>
            <option value="DISPO_DECROISSANT">DISPO DECROISSANT</option>
            <option value="NOTE_CROISSANT">NOTE CROISSANTE</option>
            <option value="NOTE_DECROISSANT">NOTE DECROISSANTE</option>
            <input type="submit" name="Choice" value="OK">
          </select>
        </form>
      </div>
      <!--Passer par l'AJAX pour un select dynamique
      <script type="text/javascript">
        const selectElement = document.querySelector('.select_button');
        selectElement.addEventListener('change',(event)=>{
          const result = document.querySelector('.result');
          var display = {event.target.value};
          $.ajax({url:'shop.php',type:'POST',data:'DISPLAY='+display});
        });
      </script>
      -->
      <h1 id = "main_title">MAGASIN</h1>
      <!--ON REGARDE COMMENT EST GÉRÉ L'AFFICHAGE DE LA BOUTIQUE-->
      <?php define('DEFAULT',0); define('CROISSANT',1); define('DECROISSANT',2); define('CATEGORY_CROISSANT',3);define('CATEGORY_DECROISSANT',4);
            define('POP_CROISSANT',5); define('POP_DECROISSANT',6); define('DISPO_CROISSANT',7); define('DISPO_DECROISSANT',8);
             define('NOTE_CROISSANT',9); define('NOTE_DECROISSANT',10);
        if(!empty($_GET['sorting_select'])){$display = $_GET['sorting_select'];}else{$display='DEFAULT';}
        switch ($display) {
          case 'DEFAULT':
            $response = SQL_request($bdd,"Select * From PRODUCTS_TAB;");
            //print_r($response);
            //echo var_dump($response['1']['Name']);
            break;
          case 'CROISSANT' :
            $response = SQL_request($bdd,"Select * From PRODUCTS_TAB Order By Price;");
            break;
          case 'DECROISSANT' :
            $response = SQL_request($bdd,"Select * From PRODUCTS_TAB Order By Price Desc;");
            break;
          case 'CATEGORY_CROISSANT' :
            $response = SQL_request($bdd,"Select * From PRODUCTS_TAB Order By Category;");
            break;
          case 'CATEGORY_DECROISSANT' :
            $response = SQL_request($bdd,"Select * From PRODUCTS_TAB Order By Category DESC;");
            break;
          case 'POP_CROISSANT' :
            $response = SQL_request($bdd,"Select * From PRODUCTS_TAB;"); //A voir
            break;
          case 'POP_DECROISSANT' :
            $response = SQL_request($bdd,"Select * From PRODUCTS_TAB;");//A voir
            break;
          case 'DISPO_CROISSANT' :
            $response = SQL_request($bdd,"Select * From PRODUCTS_TAB ORDER BY Stock;");
            break;
          case 'DISPO_DECROISSANT' :
            $response = SQL_request($bdd,"Select * From PRODUCTS_TAB ORDER BY Stock DESC;");
            break;
          case 'NOTE_CROISSANT' :
            $res = SQL_request($bdd,"Select * From COMMENTS_TAB ORDER BY Grade;"); //A voir (join SQL ?)
            $response = array();
            for($a=0;$a<count($res);$a++){
              array_push($response[$a],SQL_request($bdd,"Select * From PRODUCTS_TAB Where PRODUCT_ID = ".$res[$a]['PRODUCT_ID'].";"));
            }
            print_r($res);echo"<br><br>";
            print_r($response);
            break;
          case 'NOTE_DECROISSANT' :
            $res = SQL_request($bdd,"Select * From COMMENTS_TAB ORDER BY Grade DESC;");//A voir
            $response = array();
            for($a=1;$a<count($res);$a++){
              array_push($response[$a],SQL_request($bdd,"Select * From PRODUCTS_TAB Where PRODUCT_ID = ".$res[$a]['PRODUCT_ID'].";"));
            }
            break;
          default:
            $response = SQL_request($bdd,"Select * From PRODUCTS_TAB;");
            break;
        }
        $items_number = count($response);
      ?>
      <section class = "main">
        <!--ELEMENTS-->
        <?php for($row=0;$row<$items_number;$row++){?>
          <div class="item">
            <a href = "product_view.php?id=<?php echo $response["".$row]['Name'];?>" class="element_img">
              <img src=<?php echo "'".$response["".$row]['Location']."'";?>alt=""></a>
            <div class="element_header">
              <h4 id = "product_title"><?php echo $response["".$row]['Name'];?></h4>
              <h4 class = "price"><?php echo $response["".$row]['Price']."€";?></h4>
              <h4 class = "category"><?php echo $response["".$row]['Category'];?></h4>
              <?php //if($response["".$row]['Stock']==0){echo "Produit actuellement indisponible..."};?>
            </div>
          </div>
          <?php } ?>
      </section>
    <?php }else{ ?>
      <br><br><span id = "connection_msg">Veuillez vous connecter pour accéder aux fonctionnalités du site...</span>
    <?php } ?>

  </body>
  <footer>
		<img id="eseo_logo" src="../img/eseo_logo.png" width ="125">
    <p id="author">Made by Florentin LEPELTIER &copy;</p>
  </footer>
</html>
