<?php
include "Utilities.php";
class CFT {


    public static function validateImgType($path, $tmpName){
      $imageType = exif_imagetype($path);
      $upload_dir = dirname(__DIR__)."/img/";
      $name = Utilities::before('.' , $tmpName);
      switch ($imageType) {
          case 1:
            rename($path, $upload_dir.$name.date('U').".gif");
            return True;
            break;
          case 2:
            rename($path, $upload_dir.$name.date('U').".jpg");
            return True;
            break;
          case 3:
            // code...
            break;
          case 4:
            // code...
            break;
          case 5:
            // code...
            break;
          case 6:
            // code...
            break;
          case 7:
            // code...
            break;
          case 8:
            // code...
            break;
          case 9:
            // code...
            break;
          case 10:
            // code...
            break;
          case 11:
            // code...
            break;
          case 12:
            // code...
            break;
          case 13:
            // code...
            break;
          case 14:
            // code...
            break;
          case 15:
            // code...
            break;
          case 16:
            // code...
            break;
          case 17:
            // code...
            break;
          case 18:
            // code...
            break;

         default:
            // code...
            break;
      }

    }



}


 ?>
