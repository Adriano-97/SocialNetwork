<?php
include ("classes/DB.php");

error_reporting (E_ALL ^ E_NOTICE);

function isLoggedIn() {
    if(DB::query('SELECT user_id FROM login_tokens WHERE token = :token', array(':token' => sha1($_COOKIE['SPID'] )))){
      $user_id = DB::query('SELECT user_id FROM login_tokens WHERE token = :token', array(':token' => sha1($_COOKIE['SPID'])))[0]['user_id'];

      if(isset($_COOKIE['SPID_'])){

        return $user_id;
      } else {
        $cstrong =True;
        $token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));
        DB::query('DELETE FROM login_tokens WHERE token = :token', array(':token' => sha1($_COOKIE['SPID'])));
        DB::query('INSERT INTO login_tokens VALUES (NULL, :user_id, :token)', array(':user_id'=> $user_id, ':token'=> sha1($token)));

        setcookie("SPID", $token, time() + $one_week, '/', NULL, NULL, TRUE );
        setcookie("SPID_", '1', time() + $three_days, '/', NULL, NULL, TRUE );

        return $user_id;
      }
    }

    return false;
}



if (isLoggedIn()) {
    echo "Logged in";
    echo isLoggedIn();
} else {
    echo "Not logged in";
}
?>
