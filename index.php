<?php
include ("classes/DB.php");
include ("classes/Login.php");
$showTimeline = False;
if (Login::isLoggedIn()) {
    echo "Logged in <hr/>";
    $user_id =  Login::isLoggedIn();
    $showTimeline = True;
} else {
    echo "Not logged in";
}

if($showTimeline != False){
  $followingposts = DB::query('SELECT posts.body, posts.posted_at, posts.likes, users.username
    FROM followers, posts, users
    WHERE  posts.user_id = followers.user_id
    AND users.idusers = posts.user_id
    AND  followers.follower_id = :user_id
    ORDER BY posts.posted_at DESC', array (':user_id' => $user_id));

    foreach ($followingposts as $post) {
      echo $post['body']." ~ ".$post['username']."<hr/>";
    }
}
?>
