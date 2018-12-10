<?php
class DB {
      public static function connect() {
        $pdo = new PDO('mysql:host=localhost;dbname=socialnetwork;charset=utf8', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
      }
      public static function query($query, $params =array()) {
        $statament = self::connect()->prepare($query);
        $statament->execute($params);
        //$data = $statament->fetchAll();
        //return $data;
      }
}
?>
