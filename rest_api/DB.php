<?php
class DB {

      private $pdo;

      public  function __construct($host, $port, $dbName, $user, $password) {

        $pdo = new PDO("mysql:host={$host};port={$port};dbname={$dbName}", "{$user}", "{$password}");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this -> pdo = $pdo;

      }
      public  function query($query, $params =array()) {
        try{
          $statament = $this->pdo->prepare($query);
          $statament->execute($params);

          if (explode(' ', $query)[0] == 'SELECT'){
            $data = $statament->fetchAll();
            return $data;
          }   else {

            return True;
          }
        } catch(Exception  $e) {
            return False;
        }

      }
}
?>
