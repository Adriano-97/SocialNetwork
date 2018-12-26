<?php
include ("classes/DB.php");
include ("classes/Login.php");
include ("classes/Post.php");
$showTimeline = False;
if (Login::isLoggedIn()) {
    echo "Logged in <hr/>";
    $user_id =  Login::isLoggedIn();
    $showTimeline = True;
} else {
    die ("Not logged in");
}

if($showTimeline != False){

  $followingposts = DB::query('SELECT posts.body, posts.posted_at, posts.likes, users.username, posts.id
    FROM followers, posts, users
    WHERE  posts.user_id = followers.user_id
    AND users.idusers = posts.user_id
    AND  followers.follower_id = :user_id
    ORDER BY posts.posted_at DESC', array (':user_id' => $user_id));

    foreach ($followingposts as $post) {
      if(!DB::query('SELECT post_id FROM post_likes WHERE post_id =:post_id AND user_id = :user_id ', array(':post_id' =>$post['id'],':user_id' =>$user_id ))) {
                   echo $post['body']." ~ ".$post['username'];
                   echo "<form action= 'index.php?postid=".$post['id']."' method='post'>
                         <input type= 'submit' name= 'like' value= 'Like'>
                         <span>".$post['likes']." likes </span>
                     </form>
                   </br /><hr>";
     } else {
                  echo $post['body']." ~ ".$post['username'];
                  echo "<form action= 'index.php?postid=".$post['id']."' method='post'>
                        <input type= 'submit' name= 'unlike' value= 'Unlike'>
                        <span>".$post['likes']." likes </span>
                    </form>
                  </br /><hr>";
     }


    }
    Post::likingPost($_GET['postid'], $user_id);
}
?>
