<?php
  require("../funk.php");
  
  /*--------- DB information -----------*/
  $dsn      = 'mysql:dbname=co_19_301_99sv_coco_com;host=localhost';
  $user     = 'co-19-301.99sv-coco_c';
  $password_db = 'Em4kxvSU';
  /*------------------------------------*/
  
  $base_url = "http://co-19-301.99sv-coco.com/kadai/kadai3/kadai3_2";
  $top_url = $base_url . "/";
  $url_signup = $base_url . "/signup.php";
  $board_url = $base_url . "/board.php";

  session_start();
  if (isset($_SESSION['user_id'])) {
    header("location: $board_url");
  }

  
  $input_id = "";
  $alert_pass1 = "";
  
  $dbh = new PDO($dsn, $user, $password_db);
  $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  /* ------------------------------------     ログイン処理     ------------------------------------- */
  if (isset($_POST["input_id"]) && isset($_POST["input_password"])) {
    $input_id = $_POST["input_id"];
    $input_password = $_POST["input_password"];

    $query = 'SELECT * FROM user WHERE user_id = \'' . $input_id . '\'';
    $stmt = $dbh->query($query);

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($input_password != "" && $row["password"] == $input_password) {
      $_SESSION["user_id"] = $input_id;
      header("location: $board_url");
      exit();
    } else {
      $alert_pass1 = "IDまたはパスワードが違います";
    }

  }


  /* -------------------------------------------------------------------------------------------- */


  $dbh = null;

?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UFT-8">
  <title>簡易掲示板</title>
  <link rel="stylesheet" type="text/css" href="index.css">
</head>

<body>

  <div class="header">
    <h1><a href=<?php echo $top_url ?>>簡易掲示板</a></h1>
  </div>

  <div class="container">

    <form class="form_signup" action=<?php echo $top_url ?> method="post">
      <div>
        <p>ID</p>
        <p class="form_nav_kugiri">：</p>
        <input type="text" name="input_id" value=<?php
          echo $input_id;
        ?>>
      </div>
      <div>
        <p>パスワード</p>
        <p class="form_nav_kugiri">：</p>
        <input type="password" name="input_password">
        <button class="login_btn" type="submit">ログイン</button>
        <p class="input_alert"><?php echo $alert_pass1 ?></p>
      </div>
    </form>

    <p>初めての方は<a href=<?php echo $url_signup ?>> 登録フォームへ </a></p>

  </div>
</body>

</html>