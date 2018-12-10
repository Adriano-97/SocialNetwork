<?php
include "classes/DB.php";
$pdo = new PDO('mysql:host=localhost;dbname=socialnetwork;charset=utf8', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (isset($_POST['createaccount'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    DB::query('INSERT INTO users VALUES (\'\', :username, :password, :email)', array('username' =>$username, 'password' => $password, 'email' =>$email));
    echo "Succes";
}

 ?>


<h1>Register</h1>
<form action = "create-account.php" method = "post">
<input type = "text" name = "username" value = "" placeholder="UserName....."><p />
<input type = "password" name="password" value="" placeholder="Password....."><p />
<input type ="email" name="email" value="" placeholder="someone@site.com"><p />
<input type = "submit" name = "createaccount" value = "Create Account"><p />
</form>
