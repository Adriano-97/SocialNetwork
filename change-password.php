<?php
include ("classes/DB.php");
include ("classes/Login.php");

$tokenIsValid = False;
if (Login::isLoggedIn()) {
    if(isset($_POST['changepassword'])){

      $user_id = Login::isLoggedIn();
      $oldpassword = $_POST['oldPassword'];
      $newPassword = $_POST['newPassword'];
      $reNewPassword = $_POST['reNewPassword'];

      if(password_verify($oldpassword, DB::query('SELECT password FROM users WHERE idusers = :userid', array(':userid' => $user_id))[0]['password'])) {

            if(strlen($newPassword) >= 6 && strlen($newPassword) <= 60) {

                  if($newPassword == $reNewPassword) {
                      DB::query('UPDATE users SET password =:newPassword WHERE idusers = :userid', array(':newPassword' => password_hash($newPassword, PASSWORD_BCRYPT), ':userid' => $user_id));
                      echo "Password changed Successfully";
                  } else {
                    echo "The new passwords don't match";
                  }

            } else {
              echo "The new password entered is not valid";
            }

      } else {
        echo "Incorrect old password.";
      }
    }
}  else {
    if(isset($_GET['token'])) {
      $token = $_GET['token'];
      $idQuery = DB::query('SELECT user_id FROM password_tokens WHERE token = :token', array(':token'=> sha1($token)));
        if($idQuery != False) {
        $user_id = $idQuery[0]['user_id'];
        $tokenIsValid = True;

        if(isset($_POST['changepassword'])){
              $newPassword = $_POST['newPassword'];
              $reNewPassword = $_POST['reNewPassword'];

              if(strlen($newPassword) >= 6 && strlen($newPassword) <= 60) {

                    if($newPassword == $reNewPassword) {
                        DB::query('UPDATE users SET password =:newPassword WHERE idusers = :userid', array(':newPassword' => password_hash($newPassword, PASSWORD_BCRYPT), ':userid' => $user_id));
                        DB::query('DELETE FROM password_tokens WHERE token = :token', array(':token'=> sha1($token)));
                        echo "Password changed Successfully";
                    } else {
                        echo "The new passwords don't match";
                    }

              } else {
                    echo "The new password entered is not valid";
              }
        }
      } else {
        die("Invalid token");

      }


  } else {
    die("Not logged in");
  }
}
?>
<h1> Change Password </h1>
<form action=<?php if(!$tokenIsValid) {echo"change-password.php";} else {echo"change-password.php?token={$token}";}?> method="post">
        <?php if(!$tokenIsValid ){echo'<input type="password" name="oldPassword" value="" placeholder="Current Password"><p />';}?>
        <input type="password" name="newPassword" value="" placeholder="New Password"><p />
        <input type="password" name="reNewPassword" value="" placeholder="Repeat New Password"><p />
        <input type="submit"  name="changepassword" value="Change Password">
</form>
