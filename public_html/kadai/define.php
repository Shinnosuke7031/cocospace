<?php
/*--------- DB information -----------*/
function DBinfo () {
  $dsn      = 'mysql:dbname=co_19_301_99sv_coco_com;host=localhost';
  $user     = 'co-19-301.99sv-coco_c';
  $password_db = 'Em4kxvSU';
  
  $array = array("dsn" => $dsn, "user" => $user, "password" => $password_db);
  return $array;
}
/*------------------------------------*/

function URLs()
{
  $base_url = "http://co-19-301.99sv-coco.com/kadai/kadai4";
  $top_url = $base_url . "/";
  $url_signup = $base_url . "/signup.php";
  $board_url = $base_url . "/board.php";
  $logout_url = $base_url . "/logout.php";
  return array(
    "base" => $base_url,
    "top" => $top_url,
    "signup" => $url_signup,
    "board" => $board_url,
    "logout" => $logout_url,
    "import" => $base_url . "/import_media.php"
  );
}

function from_email_address () {
  return "sengoku731sin@gmail.com";
}
?>