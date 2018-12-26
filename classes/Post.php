<?php
class Post {
  public static function createPost($postbody, $loggedInUserId){

      if(strlen($postbody) > 160 || strlen($postbody) < 1) {
      die('Incorrect Lenght');
    }
    DB::query('INSERT INTO  posts VALUES(NULL, :postbody, NOW(), :user_id, \'0\')', array(':postbody'=> $postbody, ':user_id' => $loggedInUserId));

  }

  public static function likingPost($postId, $likerId){


    if(!DB::query('SELECT user_id FROM post_likes WHERE post_id =:post_id AND user_id = :user_id ', array(':post_id' =>$postId,':user_id' =>$likerId ))) {

      DB::query('UPDATE posts SET likes = likes + 1 WHERE id =:postid', array (':postid'=> $postId));
      DB::query('INSERT INTO post_likes VALUES(NULL,:post_id, :user_id )', array(':post_id' =>$postId, ':user_id' =>$likerId ));

    } else {

      DB::query('UPDATE posts SET likes = likes - 1 WHERE id =:postid', array (':postid'=>$postId));
      DB::query('DELETE FROM post_likes WHERE post_id =:post_id AND user_id = :user_id ', array(':post_id' =>$postId, ':user_id' =>$likerId ));

    }
  }

  public static function postForm($user_id, $userProfile, $follower_id) {
    $posts = '';
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
  return $posts;
}
}
 ?>
