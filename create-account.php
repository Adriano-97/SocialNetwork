<?php
include "classes/DB.php";
$pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=socialnetwork', 'root', '@dri@No9872');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (isset($_POST['createaccount'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    if(!DB::query('SELECT username FROM users WHERE username = :username', array(':username' => $username))) {

      if(strlen($username) >=3 && strlen($username) <=32) {

        if(preg_match('/[a-zA-Z0-9_]+/', $username)) {

          if(filter_var($email, FILTER_VALIDATE_EMAIL)) {

            if(!DB::query('SELECT email FROM users WHERE email = :email', array(':email' => $email ))) {

              if(strlen($password) >= 6 && strlen($password) <= 60) {

                DB::query('INSERT INTO users VALUES (\'\', :username, :password, :email)', array(':username' =>$username, ':password' => password_hash($password, PASSWORD_BCRYPT), ':email' =>$email));
                echo "Success";
              } else {
                echo "Invalid password";
              }
            } else {
              echo "Email was already registered";
            }
          } else {
            echo "Invalid email";
          }
        } else {
            echo "Invalid Username";
        }
      } else {
          echo "Invalid Username";
      }

    } else {
      echo "Username already taken.";
    }
}

 ?>


<h1>Register</h1>
<form action = "create-account.php" method = "post">
<input type = "text" name = "username" value = "" placeholder="UserName....."><p />
<input type = "password" name="password" value="" placeholder="Password....."><p />
<input type ="email" name="email" value="" placeholder="someone@site.com"><p />
<input type = "submit" name = "createaccount" value = "Create Account"><p />
</form>
