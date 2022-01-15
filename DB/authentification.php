<?php
	session_start();
	include_once('functions_def.php');
	$connected = false;
	$valid = (boolean) true;
	$authentification_successfull = (boolean) false;
	$id_err = "";$pwd_err = "";

	//Si la connexion est déjà établie on revient à l'accueil
	if(isset($_SESSION['id'])){
		$connected = true;
		echo "VOUS ETES DEJA CONNECTE";
		echo '<meta http-equiv="refresh" content="1;URL=../index.php">';
		//Exit();
	}

	if(!empty($_POST)){
		extract($_POST);
		if(isset($_POST['CONNEXION'])){
			$id = (String) trim($id);
			$pwd = (String) trim($pwd);

			//Vérif de la saisie des données
			if(empty($id)){$valid = false;$id_err = "Ce champ est obligatoire";}
			if(empty($pwd)){$valid = false;$pwd_err = "Ce champ est obligatoire";}

			if($valid){
				//Requête SQL
				$req = $bdd->prepare("Select * From USERS_TAB Where Email = ?;");
				$req->execute(array($id));
				$result = $req->fetchAll();
				if($result!=null){
					//Décryptage mdp
					$hash = $result[0]['Password_'];
					if((($result[0]['USER_ID'])!="") && password_verify($pwd,$hash)){ //Si le gars a vérifié son compte
						$authentification_successfull = true;
					}
				}
				if($authentification_successfull){
					$_SESSION['USER_ID'] = $result[0]['USER_ID'];
					$_SESSION['Last_name'] = $result[0]['Last_name'];
					$_SESSION['First_name'] = $result[0]['First_name'];
					$_SESSION['Email'] = $result[0]['Email'];
					$_SESSION['Password_'] = $result[0]['Password_'];
					$_SESSION['Status'] = $result[0]['Status'];
					$_SESSION['Checking'] = $result[0]['Checking'];
					$connected = true;
					//echo "AUTHENTIFICATION SUCCESSFULL";
				}
			}
		}
	}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>WEBSITE-Connexion</title>
    <link rel="stylesheet" type="text/css" href="../css/base.css">
		<link rel="stylesheet" type="text/css" href="../css/authentification.css">
    <link href="https://fonts.googleapis.com/css2?family=Electrolize&display=swap" rel="stylesheet">
  </head>
  <body>
		<!--BARRE DE MENU-->
    <h1 class = "TITLE" id = "website_title"> 3D FAMILY</h1>
    <div class="MENU" id = "nav_bar">
      <a href="../index.php" class = "nav_bar_link">Accueil</a>
      <a href="../website_pages/game.php" class = "nav_bar_link">Jeux</a>
      <a href="../website_pages/shop.php" class = "nav_bar_link">Magasin</a>
      <a href="../website_pages/profile.php" class = "nav_bar_link">Profil</a>
      <a href="../website_pages/admin.php" class = "nav_bar_link">Admin</a>
			<a href="registration.php" class = "nav_bar_link">Inscription</a>
      <?php if(!$connected){?>
			  <a href="#" class = "nav_bar_link">Connexion</a>
      <?php }else{?>
        <a href="log_out.php" class = "nav_bar_link">Deconnexion</a>
      <?php } ?>
    </div>
		<!--FORMULAIRE DE CONNEXION-->
		<h1 class = auth_title >Veuillez vous connecter</h1>
		<div class="form">
			<form action="#" method="POST">
				<input type="text" name="id" placeholder="Identifiant(E-mail)" required>
				<?php if(!$valid){echo $id_err;}?>
				<br><br>
				<input type="password" name="pwd" placeholder="Mot de passe" required>
				<?php if(!$valid){echo $pwd_err;}?>
				<br><br>
				<input type="submit" name="CONNEXION" value="CONNEXION">
			</form>
		</div>
		<br>
		<?php if($connected){ ?>
			<span style = "font-family:'Electrolize';text-align:center;">AUTHENTIFICATION REUSSIE, vous allez être redirigé vers l'accueil...</span>
		<?php echo '<meta http-equiv="refresh" content="2;URL=../index.php">'; ?>
		<?php }elseif(!$connected && (isset($_POST['CONNEXION']))){ ?>
			<span style = "font-family:'Electrolize';text-align:center;">CES IDENTIFIANTS NE SONT PAS VALIDES...</span>
		<?php } ?>
  </body>


  <footer>
		<img id="eseo_logo" src="../img/eseo_logo.png" width ="125">
    <p id="author">Made by Florentin LEPELTIER &copy;</p>
  </footer>
</html>
