<<?php
class Utilities {

  public static function before ($delimiter, $string){
      return substr($string, 0, strpos($string, $delimiter));
  }

}
