<?php
include ("classes/DB.php");
include ("classes/Login.php");

    if(!Login::isLoggedIn()) {
        die("Not logged in!");
    }

    if(isset($_POST['confirm'])) {

          if(isset($_POST['allDevices'])) {
                DB::query('DELETE FROM login_tokens WHERE user_id = :user_id', array(':user_id' => Login::isLoggedIn()));

          } else {
                if(isset($_COOKIE['SPID'])) {
                    DB::query('DELETE FROM login_tokens WHERE token = :token', array(':token' => sha1($_COOKIE['SPID'])));
                }
                setcookie('SPID', '1', time() - 3600);
                setcookie('SPID_', '1', time() - 3600);
          }
          //This code will force the page to refresh so the user see is logged out.
          $page = $_SERVER['PHP_SELF'];
          echo '<meta http-equiv="Refresh" content="0;' . $page . '">';

    }
?>

<h1>Logout of your Account?</h1>
<p>Are you sure you will like to logout?</p>
<form action="logout.php" method="post">
          <input type="checkbox" name="allDevices"  value="checked"> Logout of all devices.<br />
          <input type="submit" name="confirm" value = "Confirm">
</form>
