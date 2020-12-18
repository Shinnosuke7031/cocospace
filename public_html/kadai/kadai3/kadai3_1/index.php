<?php
  $base_url = "http://co-19-301.99sv-coco.com/kadai/kadai3/kadai3_1";
  $top_url = $base_url . "/";
  $url_signup = $base_url . "/signup.php";

  $input_id = "";
  $alert_pass1 = "";
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
    
    <form class="form_signup" action=<?php echo $url_signup ?> method="post">
      <div>
        <p>ID</p>
        <p class="form_nav_kugiri">：</p>
        <input type="text" name="input_id" value=<?php
          echo $input_id;
        ?> >
      </div>
      <div>
        <p>パスワード</p>
        <p class="form_nav_kugiri">：</p>
        <input type="password" name="input_password">
        <button class="login_btn" type="submit">ログイン</button>
      </div>
        <p class="input_alert"><?php echo $alert_pass1 ?></p>
    </form>

    <p>初めての方は<a href=<?php echo $url_signup ?> > 登録フォームへ </a></p>
  
  </div>
  </body>
</html>