<?php
  include_once('db_connection.php');
/**************COMMUNICATION AVEC LA BASE DE DONNEES************/
function SQL_request($bdd,$string){
  $req = $bdd->prepare($string);
  $req->execute();
  $response = $req->fetchAll();
  $req->closeCursor();
  return $response;
}

function simple_SQL_request($bdd,$string,$response){
  $req = $bdd->prepare($string);
  $req->execute();
  $req->closeCursor();
  return $response;
}

/****************GESTION DU PANIER**********************/
function create_shopping_cart(){
  //On créer le panier
  if(!isset($_SESSION['shopping_cart'])){
    $_SESSION['shopping_cart'] = array();
    $_SESSION['shopping_cart']['PRODUCT_ID'] = array();
    $_SESSION['shopping_cart']['Name'] = array();
    $_SESSION['shopping_cart']['Qty'] = array();
    $_SESSION['shopping_cart']['Price'] = array();
    $_SESSION['shopping_cart']['Total_cost'] = array();
    $_SESSION['shopping_cart_state'] = 0;
  }
}

function compute_final_cost(){
  //Calcul du coup total
  $finalCost = 0;
  for($i=0;$i<count($_SESSION['shopping_cart']['PRODUCT_ID']);$i++){
    (float)$finalCost += (float)$_SESSION['shopping_cart']['Qty'][$i]*(float)$_SESSION['shopping_cart']['Price'][$i];
  }
  $_SESSION['shopping_cart']['Total_cost'] = (float)$finalCost; //Somme de Qty*Prcie par produit
}

function add_item_to_shopping_cart($response,$quantity){
  //On regarde si le produit a déjà été enregistré dans le panier
  $alreadyInCart = false;
  for($i=0;$i<count($_SESSION['shopping_cart']['PRODUCT_ID']);$i++){
    if($response[0]['PRODUCT_ID']==$_SESSION['shopping_cart']['PRODUCT_ID'][$i]){
      $alreadyInCart = true;
      $_SESSION['shopping_cart']['Qty'][$i] += $quantity;
    }
  }
  if(!$alreadyInCart){
    //On enregistre le produit comme nouveau dans le panier
    $_SESSION['shopping_cart_state'] = 1; //Le panier n'est plus vide
    array_push($_SESSION['shopping_cart']['PRODUCT_ID'],$response[0]['PRODUCT_ID']);
    array_push($_SESSION['shopping_cart']['Name'],$response[0]['Name']);
    array_push($_SESSION['shopping_cart']['Price'],$response[0]['Price']);
    array_push($_SESSION['shopping_cart']['Qty'],$quantity);
  }
  compute_final_cost(); //$_SESSION['shopping_cart']['Total_cost'] = ...;
  //var_dump($_SESSION['shopping_cart']);
  echo "Cet article a bien été ajouté au panier";
}

function delete_items_from_shopping_cart(){
  $_SESSION['shopping_cart_state'] == 0;
  unset($_SESSION['shopping_cart']);
  echo '<meta http-equiv="refresh" content="0;URL=shopping_cart.php">';
}

function check_balance($bdd){
  //On vérifie que l'utilisation dispose d'assez d'argent
  $sqlRequest = SQL_request($bdd,"Select Balance From USERS_TAB Where USER_ID = ".$_SESSION['USER_ID'].";");
  $balance = (float)$sqlRequest[0]['Balance'];
  $_SESSION['Balance'] = $balance;
  $cost = (float)$_SESSION['shopping_cart']['Total_cost'];
  $result = $balance-$cost;
  //var_dump($cost);
  //var_dump($balance);
  return $result;
}

function confirm_order($bdd){
  //On vérifie que l'utilisateur a vérifié son compte
  $stringRequest = "Select * From USERS_TAB Where USER_ID = ".$_SESSION['USER_ID']." And Checking = ".$_SESSION['Checking'].";";
  $request = SQL_request($bdd,$stringRequest);
  if($request[0]['Checking'] == 0){
    echo "Vous devez vérifier votre compte avant de passer commande. Rendez-vous dans votre boite mail pour finaliser votre inscription";
    echo "<br><br>Redirection vers l'accueil en coiurs...";
    echo '<meta http-equiv="refresh" content="4;URL=../index.php">';
  }
  else {
    compute_final_cost();
    //On enregistre la commande dans la base de données
    $order_submitted = true;
    //On commence par la facture
    $order_confirmation = $bdd->prepare("Insert Into INVOICES_TAB (USER_ID,Total_cost)VALUES(?, ?);");
    $order_confirmation->execute(array((int)$_SESSION['USER_ID'],(float)$_SESSION['shopping_cart']['Total_cost']));
    if($order_confirmation==null){$order_submitted=false;}
    $order_confirmation->closeCursor();
    //On récupère l'ID de la facture en cours d'édition
    $INVOICE_ID = SQL_request($bdd,"Select Max(INVOICE_ID) From INVOICES_TAB;");
    //Puis chaque article est indépendamment enregistré en bdd
    for($i=0;$i<count($_SESSION['shopping_cart']['PRODUCT_ID']);$i++){
      $order_confirmation = $bdd->prepare("Insert Into ORDERS_TAB (INVOICE_ID,PRODUCT_ID,Qty,Price)VALUES(?, ?, ?, ?);");
      $order_confirmation->execute(array((int)$INVOICE_ID[0]['Max(INVOICE_ID)'],(int)$_SESSION['shopping_cart']['PRODUCT_ID'][$i],(int)$_SESSION['shopping_cart']['Qty'][$i],(float)$_SESSION['shopping_cart']['Qty'][$i]*(float)$_SESSION['shopping_cart']['Price'][$i]));
      if($order_confirmation==null){$order_submitted=false;}
      $order_confirmation->closeCursor();
    }
    //On débite l'utilisateur
    $newBalance = (float)$_SESSION['Balance']-(float)$_SESSION['shopping_cart']['Total_cost'];
    $req = $bdd->prepare("Update USERS_TAB Set Balance = ? Where USER_ID = ?;");
    $req->execute(array($newBalance,$_SESSION['USER_ID']));
    $req->closeCursor();
    if($req == null){
      $order_confirmation = false;
    }

    if($order_submitted){
      echo "PAIEMENT ACCEPTÉ\n";
      echo "Merci pour votre commande\n";
      echo "Vous allez être redirigé vers l'accueil...\n";
      echo '<meta http-equiv="refresh" content="4;URL=../index.php">';
      unset($_SESSION['shopping_cart']); //On vide le panier
      unset($_SESSION['Balance']); //On préfèrera détruire cette var
    }
    else{
      echo "Erreur lors de l'enregistrement dans la base de données...\n";
      echo "Vous allez être redirigé vers l'accueil...\n";
      echo '<meta http-equiv="refresh" content="4;URL=../index.php">';
    }
  }
}


?>
