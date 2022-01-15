<?php
class DB_connection{
  private $host = 'localhost';
  private $name = 'BDFlorentinLepeltier';
  private $user = 'root';
  private $password = ''; //Vide sous windows
  private $connection;

  function __construct($host = null, $name = null, $user = null, $password = null){
    if($host != null){
      $this->host = $host;
      $this->name = $name;
      $this->user = $user;
      $this->password = $password;
    }
    //echo $this->host;
    try{
      $this->connection = new PDO('mysql:host='.$this->host.';dbname='.$this->name,
      $this->user,$this->password,array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8',
      PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    }
    catch(PDOException $e){
      echo 'ERREUR : Impossible de se connecter à la base de données';
      echo $e->getMessage();
      die();
    }
  }

  public function connection(){
    return $this->connection;
  }
}
$db = new DB_connection; //Instanciation de la classe
$bdd = $db->connection(); //la connexion est établie et stockée dans l'attribut de la classe
?>
