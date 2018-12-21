<?php
include ("classes/DB.php");
include ("classes/Login.php");

$userProfile = '';
if(isset($_GET['userprofile'])){

  $userProfile = $_GET['userprofile'];
  $isFollowing = False;
  $follower_id = Login::isLoggedIn();
  $idQuery = DB::query('SELECT idusers FROM users WHERE username = :username ', array(':username'=> $userProfile));
  $user_id = $idQuery[0]['idusers'];
  $selectQuery = DB::query('SELECT follower_id FROM followers WHERE user_id = :user_id AND follower_id=:follower_id', array(':user_id'=> $user_id, ':follower_id' => $follower_id));
  //$likeQuery = DB::query('SELECT post_id FROM post_likes WHERE post_id =:post_id AND user_id = :user_id ', array(':post_id' =>$_GET['postid'],':user_id' =>$follower_id ));

  if($idQuery) {

    if($selectQuery) {
      $isFollowing = True;
    }
    if (!($user_id == $follower_id)) {
      if(isset($_POST['follow'])) {
        if(Login::isLoggedIn() != False){

          if(!$selectQuery) {
            DB::query('INSERT INTO followers VALUES (NULL, :userid, :follower_id)', array(':userid'=> $user_id, ':follower_id' => $follower_id));
          } else {
            echo "Already following";
          }
        } else {
          echo "You must be loging to follow an user.";
        }
        $isFollowing = True;
      } else {
        //do nothing
      }
    } else {
      echo "Your profile";
    }

    if (!($user_id == $follower_id)) {
      if(isset($_POST['unfollow'])) {

        if($selectQuery) {
          DB::query('DELETE FROM followers WHERE user_id=:user_id AND follower_id=:follower_id', array(':user_id'=> $user_id, ':follower_id' => $follower_id));
          $isFollowing = False;
        } else {
          echo "Not Following";
        }
      }
    }

    if(isset($_POST['post'])) {
        $postbody = $_POST['postbody'];


        if(strlen($postbody) > 160 || strlen($postbody) < 1) {
          die('Incorrect Lenght');
        }
        DB::query('INSERT INTO  posts VALUES(NULL, :postbody, NOW(), :user_id, \'0\')', array(':postbody'=> $postbody, ':user_id' => $follower_id));

    }

    if(isset($_GET['postid'])){
      if(!DB::query('SELECT user_id FROM post_likes WHERE post_id =:post_id AND user_id = :user_id ', array(':post_id' =>$_GET['postid'],':user_id' =>$follower_id ))) {

        DB::query('UPDATE posts SET likes = likes + 1 WHERE id =:postid', array (':postid'=> $_GET['postid']));
        DB::query('INSERT INTO post_likes VALUES(NULL,:post_id, :user_id )', array(':post_id' =>$_GET['postid'], ':user_id' =>$follower_id ));

      } else {

        DB::query('UPDATE posts SET likes = likes - 1 WHERE id =:postid', array (':postid'=> $_GET['postid']));
        DB::query('DELETE FROM post_likes WHERE post_id =:post_id AND user_id = :user_id ', array(':post_id' =>$_GET['postid'], ':user_id' =>$follower_id ));

      }
    }

    $dbposts = DB::query('SELECT * FROM posts WHERE user_id = :userid ORDER BY id DESC',array(':userid' => $user_id));
    foreach ($dbposts as $p) {

       if(!DB::query('SELECT post_id FROM post_likes WHERE post_id =:post_id AND user_id = :user_id ', array(':post_id' =>$p['id'],':user_id' =>$follower_id ))) {
         $posts .= htmlspecialchars($p['body'])."
                    <form action= 'profile.php?userprofile=$userProfile&postid=".$p['id']."' method='post'>
                          <input type= 'submit' name= 'like' value= 'Like'>
                          <span>".$p['likes']." likes </span>
                      </form>
                    </br /><hr>";
      } else {
        $posts .= htmlspecialchars($p['body'])."
                   <form action= 'profile.php?userprofile=$userProfile&postid=".$p['id']."' method='post'>
                         <input type= 'submit' name= 'unlike' value= 'Unlike'>
                         <span>".$p['likes']." likes </span>
                     </form>
                   </br /><hr>";
      }
    }

  } else {
    die('User not found');
  }

} else {
  die('User not found');
}


?>
<h1> <?php echo $userProfile; ?>'s Profile</h1>
  <form action="profile.php?userprofile=<?php echo $userProfile; ?>" method="post">
    <?php
      if (!($user_id == $follower_id)) {
        if (!$isFollowing) {
          echo '<input  type="submit" name="follow" value="Follow"><p />';
        } else {
          echo '<input type ="submit" name= "unfollow" value = "Unfollow"><p />';
        }
      }
    ?>
  </form>
  <?php if($user_id == $follower_id ) {
    echo '<form action="profile.php?userprofile=';echo $userProfile; echo' " method="post">
      <textarea name="postbody" rows="8" cols="80"> </textarea>
      <input type="submit" name="post" value = "Post" >
    </form>';
  }
    ?>

  <div class="posts">
      <?php  echo $posts;?>
  </div>
