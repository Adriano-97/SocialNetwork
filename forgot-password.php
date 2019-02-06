<?php
include('./classes/DB.php');
error_reporting (E_ALL ^ E_NOTICE);

if  (isset($_POST['submit'])) {

    $cstrong = True;
    $email= $_POST['email'];
    $token = bin2hex(openssl_random_pseudo_bytes(42, $cstrong));
    $user_id = DB::query('SELECT idusers FROM users WHERE email = :email', array(':email' => $email))[0]['idusers'];

    if($user_id != False){
      DB::query('INSERT INTO password_tokens VALUES(NULL, :token, :user_id)', array('token'=> sha1($token), ':user_id' => $user_id));
      echo "Email sent";
      echo '</br>';
      echo $token;
    } else {
        echo "Not a valid email";
    }
}
 ?>
 <h1> Forgot Password </h1>
 <form action="forgot-password.php" method="post">
   <input type="text" name="email" value="" placeholder="Email...."><p />
   <input type="submit" name="submit" placeholder="Submit">
 </form>
