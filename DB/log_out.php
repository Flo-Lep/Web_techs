<?php
  session_start();
  session_destroy();
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
    <h1 style = "font-family:'Electrolize';text-align:center;">Deconnexion en cours...</h1>
  </body>
</html>
<?php
  echo '<meta http-equiv="refresh" content="1;URL=../index.php">';
  Exit();
?>
