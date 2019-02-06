<?php
class DB {
      public static function connect() {
        $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=socialnetwork', 'root', '@dri@no9872');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
      }
      public static function query($query, $params =array()) {
        $statament = self::connect()->prepare($query);
        $statament->execute($params);
        if (explode(' ', $query)[0] == 'SELECT'){

          $data = $statament->fetchAll();
          return $data;
        }
      }
}
?>
