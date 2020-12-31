<?php
$base_url = "http://co-19-301.99sv-coco.com/kadai/kadai3/kadai3_3";
$top_url = $base_url . "/";
$url_signup = $base_url . "/signup.php";
$board_url = $base_url . "/board.php";
$logout_url = $base_url . "/logout.php";

session_start();
$_SESSION = array();//セッションの中身をすべて削除
session_destroy();//セッションを破壊
?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UFT-8">
    <title>簡易掲示板</title>
    <link rel="stylesheet" type="text/css" href="index.css">
  </head>
<body>

  <div class="header2">
    <h1><a href=<?php echo $top_url ?>>簡易掲示板</a></h1>
  </div>

  <div class="container">

    <p>ログアウトしました</p>

    <p><a href=<?php echo $top_url ?> > トップページへ </a></p>
  </div>
  </body>
</html>