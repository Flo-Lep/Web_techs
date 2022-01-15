<?php
  include_once('../DB/functions_def.php');
  session_start();
  $connected = false;
  //Vérif si le gars est co
  if(isset($_SESSION['USER_ID'])){
    $connected = true;
  }


  if($connected){
    //On vérifie que le bouton payer a été pressé
    if(isset($_POST['proceed_payment'])){
      if(check_balance($bdd)<0){
        echo "Vous n'avez pas assez de monnaie sur votre compte..."."<br><br>"."Jouez aux jeux pour tenter de reporter de la monnaie !"."<br><br>";
        echo "Redirection vers l'accueil en cours...";
        //echo '<meta http-equiv="refresh" content="4;URL=../index.php">';
      }
      else{
        confirm_order($bdd);
      }
    }
    //Sinon on l'affiche
    else{?>
      <h1>MONTANT A REGLER : </h1><br><?php echo $_SESSION['shopping_cart']['Total_cost']."€";?><br><br>
      <form class="proceed_payment" action="#" method="post">
        <input type="submit" name="proceed_payment" value="PAYER EN LIGNE">
      </form>
    <?php
    }
  }
  else{
    echo "ERREUR, vous n'êtes plus connecté...veuillez renouveler l'opération...";
    //echo '<meta http-equiv="refresh" content="4;URL=../index.php">';
  }
?>
