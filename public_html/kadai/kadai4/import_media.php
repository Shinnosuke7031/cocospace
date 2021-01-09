<?php
  $base_url = "http://co-19-301.99sv-coco.com/kadai/kadai4/";
  $top_url = $base_url . "/";
  $url_signup = $base_url . "/signup.php";
  $board_url = $base_url . "/board.php";
  $logout_url = $base_url . "/logout.php";

  require("../funk.php");
  require("../define.php");
  $DBinfo = DBinfo();
  $dsn = $DBinfo["dsn"];
  $user = $DBinfo["user"];
  $password_db = $DBinfo["password"];

  if(isset($_GET["target"]) && $_GET["target"] !== ""){
    $target = $_GET["target"];
  }
  else{
    header("Location: $board_url");
  }
  $MIMETypes = array(
    'png' => 'image/png',
    'jpeg' => 'image/jpeg',
    'gif' => 'image/gif',
    'mp4' => 'video/mp4'
  );
  try {
    $pdo = new PDO($dsn, $user, $password_db);
    $sql = "SELECT * FROM posts WHERE fname = :target;";
    $stmt = $pdo->prepare($sql);
    $stmt -> bindValue(":target", $target, PDO::PARAM_STR);
    $stmt -> execute();
    $row = $stmt -> fetch(PDO::FETCH_ASSOC);
    header("Content-Type: ".$MIMETypes[$row["extension"]]);
    echo ($row["raw_data"]);
  }
  catch (PDOException $e) {
    echo("<p>500 Inertnal Server Error</p>");
    exit($e->getMessage());
  }
?>