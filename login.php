<?php
include ("classes/DB.php");
$pdo = DB::connect();
if (isset($_POST['login'])) {

        $username = $_POST['username'];
        $password = $_POST['password'];

        if(DB::query('SELECT username FROM users WHERE username = :username', array(':username' => $username))) {
            if(password_verify($password, DB::query('SELECT password FROM users WHERE username= :username', array(':username' => $username))[0]['password'])) {
              echo "Logged in";
              $cstrong =True;
              $token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));
              $user_id = DB::query('SELECT idusers FROM users WHERE username = :username', array(':username' => $username))[0]['idusers'];
              $one_week = 604804;
              $three_days = 259200;
              echo $token;
              DB::query('INSERT INTO login_tokens VALUES (NULL, :user_id, :token)', array(':user_id'=> $user_id, ':token'=> sha1($token)));

              setcookie("SPID", $token, time() + $one_week, '/', NULL, NULL, TRUE );
              setcookie("SPID_", '1', time() + $three_days, '/', NULL, NULL, TRUE );
              
            } else {
              echo "Incorrect password";
            }
        } else {
          echo "User dosn't exists";
        }
}
 ?>
<h1>Loging to your account </h1>
<form action= "login.php" method= "post">
<input type="text"  name="username" value="" placeholder="Username...."><p/>
<input type="password"  name="password" value=""  placeholder="Password...."><p/>
<input type="submit" name="login"  value="Login"><p/>
</form>
