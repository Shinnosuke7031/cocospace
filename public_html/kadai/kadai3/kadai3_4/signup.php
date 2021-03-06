<?php
  require("../funk.php");
  require("./define.php");
  $DBinfo = DBinfo();
  $dsn = $DBinfo["dsn"];
  $user = $DBinfo["user"];
  $password_db = $DBinfo["password"];
  
  $base_url = "http://co-19-301.99sv-coco.com/kadai/kadai3/kadai3_4";
  $top_url = $base_url . "/";
  $url_signup_complete = $base_url . "/signup_complete.php";
  $url_signup = $base_url . "/signup.php";

  $alert_name = "";
  $alert_to = "";
  $alert_pass1 = "";
  $alert_pass2 = "";
  $input_name = "";
  $input_to = "";
  $input_password = "";
  $isFormComplete = false;

  /* ------------------------------------     仮登録までの処理     ------------------------------------- */
  if (isset($_POST["input_name"]) && isset($_POST["input_to"]) && isset($_POST["input_password"]) && isset($_POST["check_password"])) {
    $alert_name = $_POST["input_name"] != "" ? "" : "入力必須です";
    $alert_to = $_POST["input_to"] != "" ? "" : "入力必須です";
    $alert_pass1 = $_POST["input_password"] != "" ? "" : "入力必須です";
    $alert_pass2 = $_POST["check_password"] != "" ? "" : "入力必須です";
    $input_name = $_POST["input_name"];
    $input_to = $_POST["input_to"];
    $input_password = $_POST["input_password"];
    $check_password = $_POST["check_password"];
    if ($input_password != $check_password) {
      $alert_pass2 = "パスワードが一致しません";
    }
    //  パスワードが一致していて、各フォームが空白ではない時に仮登録
    else if ($input_name != "" && $input_password != "" && $check_password != "") {
      //仮登録状態でDBに保存
      // $isFormComplete = true;
      $urltoken = random(48);
      $url = $url_signup_complete . "?urltoken=" . $urltoken;

      /* ---------------------------------------- メール送信機能 ------------------------------------------- */
      mb_language("Japanese");
      mb_internal_encoding("UTF-8");
      $title = "[ココスペース インターン 掲示板]仮登録が完了しました";
      $message = "ココスペースインターン掲示板に仮登録ありがとうございます。\r\n以下のURLをクリックして本登録を完了してください。\r\n" . $url;
      $headers = "From: " . from_email_address();
      if (mb_send_mail($_POST["input_to"], $title, $message, $headers)) {
        $isFormComplete = true;
      }

      /* ------------------------------------------------------------------------------------------------- */

      try{
        $dbh = new PDO($dsn, $user, $password_db);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $user_id = random(8);
        $isTemporary = true;
        $time_temporary = now();

        $query = 'SELECT * FROM user';
        $stmt = $dbh->query($query);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          if ($row["user_id"] == $user_id) $user_id = random(8);// 他のユーザーとuser_idが被ったらもう一度生成
        }
        
        $query = 'INSERT INTO user(user_id, name, password, isTemporary, time_temporary, urltoken) VALUES(:user_id, :name, :password, :isTemporary, :time_temporary, :urltoken)';
        $stmt = $dbh->prepare($query);

        $stmt -> bindValue(':user_id', $user_id, PDO::PARAM_STR_CHAR);
        $stmt -> bindValue(':name', $input_name, PDO::PARAM_STR_CHAR);
        $stmt -> bindValue(':password', $input_password, PDO::PARAM_STR_CHAR);
        $stmt -> bindValue(':isTemporary', $isTemporary, PDO::PARAM_INT);
        $stmt -> bindValue(':time_temporary', $time_temporary, PDO::PARAM_STR_CHAR);
        $stmt -> bindValue(':urltoken', $urltoken, PDO::PARAM_STR_CHAR);
        
        $stmt->execute();

        $dbh = null;
      }catch(PDOException $e){
        print("データベースの接続に失敗しました".$e->getMessage());
        die();
      }

    }
  }
  /* -------------------------------------------------------------------------------------------- */

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
    
    <h1 class="page_title">登録フォーム</h1>

    <?php if (!$isFormComplete): ?>
    <!-- 入力フォーム -->
    <form class="form_signup" action=<?php echo $url_signup ?> method="post">
      <div>
        <p>名前</p>
        <p class="form_nav_kugiri">：</p>
        <input type="text" name="input_name" value=<?php
          echo $input_name;
        ?> >
        <p class="input_alert"><?php echo $alert_name ?></p>
      </div>
      <div>
        <p>メールアドレス</p>
        <p class="form_nav_kugiri">：</p>
        <input type="email" name="input_to" value=<?php
          echo $input_to;
        ?> >
        <p class="input_alert"><?php echo $alert_to ?></p>
      </div>
      <div>
        <p>パスワード</p>
        <p class="form_nav_kugiri">：</p>
        <input type="password" name="input_password" value=<?php
          echo $input_password;
        ?> >
        <p class="input_alert"><?php echo $alert_pass1 ?></p>
      </div>
      <div>
        <p>パスワード(確認)</p>
        <p class="form_nav_kugiri">：</p>
        <input type="password" name="check_password">
        <p class="input_alert"><?php echo $alert_pass2 ?></p>
        <button class="signup_btn btn" type="submit">登録</button>
      </div>
    </form>

    <?php else: ?>
    <!-- 登録完了 -->
    <p>入力されたメールアドレスにメールを送信しました。</p>
    <p>メールの案内にそって本登録を完了してください。</p>
    <?php endif; ?>
  </div>



  </body>
</html>