<?php
include ("classes/DB.php");
$pdo = DB::connect();
if (isset($_POST['login'])) {

        $username = $_POST['username'];
        $password = $_POST['password'];

        if(DB::query('SELECT username FROM users WHERE username = :username', array(':username' => $username))) {
            if(password_verify($password, DB::query('SELECT password FROM users WHERE username= :username', array(':username' => $username))[0]['password'])) {
              echo "Logged in";
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
