<?php
class Comments {
  public static function createComment($commentBody, $user_id, $post_id){

      if(strlen($commentBody) > 160 || strlen($commentBody) < 1) {
      die('Incorrect Lenght');
    }
    DB::query('INSERT INTO  comments VALUES(NULL, :commentBody, :user_id, NOW(), :post_id)', array(':commentBody'=> $commentBody, ':user_id' => $user_id, ':post_id' => $post_id));

  }

  public static function showComment($postid){
    $comments = DB::query('SELECT comments.comment, users.username
                           FROM comments, users
                           WHERE post_id = :postid AND comments.user_id = users.idusers
                           ORDER BY comments.id ASC',
                           array(':postid'=> $postid));
    foreach($comments as $com) {
      echo $com['comment']." ~ ".$com['username']."</br>";
    }
  }
}
?>
