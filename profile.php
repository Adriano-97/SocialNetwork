<?php
include ("classes/DB.php");
include ("classes/Login.php");

$userProfile = '';
if(isset($_GET['userprofile'])){
  $userProfile = $_GET['userprofile'];
  $idQuery = DB::query('SELECT idusers FROM users WHERE username = :username ', array(':username'=> $userProfile));
  if($idQuery) {

    if(isset($_POST['follow'])) {
      if(Login::isLoggedIn() != False){
        $user_id = $idQuery[0]['idusers'];
        $follower_id = Login::isLoggedIn();

        if(!DB::query('SELECT follower_id FROM followers WHERE user_id = :user_id', array(':user_id' => $user_id))) {
          DB::query('INSERT INTO followers VALUES (NULL, :userid, :follower_id)', array(':userid'=> $user_id, ':follower_id' => $follower_id));
        } else {
          echo "Already following";
        }
      } else {
        echo "You must be loging to follow an user.";
      }
    } else {
      //do nothing
    }
  } else {
    die('User not found');
  }

} else {

  die('User not found');
}

?>
<h1> <?php echo $userProfile; ?>'s Profile<h1>
  <form action="profile.php?userprofile=<?php echo $userProfile; ?>" method="post">
    <input  type="submit" name="follow" value="Follow">
  </form>
