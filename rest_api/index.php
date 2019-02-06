<?php
require_once("DB.php");
require_once(dirname(__DIR__)."/classes/CFT.php");
$db = new DB("127.0.0.1", "3306", "socialnetwork", "root", "@dri@No9872");


if ($_SERVER['REQUEST_METHOD'] == "GET"){

  if($_GET['url'] == 'auth'){

  } else if ($_GET['url'] == "users") {

  } else if ($_GET['url'] == "upload") {

  } else {
    http_response_code(404);
  }

} else if ($_SERVER['REQUEST_METHOD'] == "POST") {

    if($_GET['url'] == 'auth'){
        $postBody = file_get_contents("php://input");
        $postBody = json_decode($postBody);

        $username = $postBody -> username;
        $password = $postBody -> password;

        if($db->query('SELECT username FROM users WHERE username = :username', array(':username' => $username))) {
            if(password_verify($password, $db->query('SELECT password FROM users WHERE username= :username', array(':username' => $username))[0]['password'])) {

                  $token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));
                  $user_id = $db->query('SELECT idusers FROM users WHERE username = :username', array(':username' => $username))[0]['idusers'];
                  $db->query('INSERT INTO login_tokens VALUES (NULL, :user_id, :token)', array(':user_id'=> $user_id, ':token'=> sha1($token)));
                  echo '{ "Token": "'.$token.'"}';

            } else {
                  http_response_code(401);
            }
        } else {
          http_response_code(401);
        }
    } else if ($_GET['url'] == 'upload') {
        if(is_uploaded_file($_FILES["user_image"]["tmp_name"])&& $_POST['user_id']) {

          $tmp_file = $_FILES['user_image']["tmp_name"];
          $img_name = $_FILES['user_image']['name'];
          $upload_dir = dirname(__DIR__)."/temp/".date('U');
          $query = "INSERT INTO user_img VALUES (NULL, '{$_POST["user_id"]}','{$img_name}')";


          if(move_uploaded_file($tmp_file, $upload_dir) && $db->query($query) ){
                  echo '{ "Status": "Success"}';
                  CFT::validateImgType($upload_dir, $img_name);
                  http_response_code(200);
          } else {
                  echo '{ "Status": "upload faild"}';
                  CFT::validateImgType($upload_dir, $img_name);
                  http_response_code(400);
          }

        } else {
          http_response_code(400);
        }
    } else {
      http_response_code(400);
    }
} else if ($_SERVER['REQUEST_METHOD'] == "DELETE") {
    if(isset($_GET['token'])) {
        if($db->query('SELECT token FROM login_tokens WHERE token = :token', array(':token' => sha1($_GET['token'])))) {
            $db->query('DELETE FROM login_tokens WHERE token = :token', array(':token' => sha1($_GET['token'])));
            echo '{ "Status": "Success"}';
            http_response_code(200);
        } else {
            echo 'Invalid token';
            http_response_code(400);
        }
    } else {
      echo "Bad Request";
      http_response_code(400);
    }

} else {
    http_response_code(405);
}
 ?>
