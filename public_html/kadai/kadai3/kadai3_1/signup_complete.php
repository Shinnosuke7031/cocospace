<?php
  /*--------- DB information -----------*/
  $dsn      = 'mysql:dbname=co_19_301_99sv_coco_com;host=localhost';
  $user     = 'co-19-301.99sv-coco_c';
  $password_db = 'Em4kxvSU';
  /*------------------------------------*/
  
  require("../funk.php");

  //成功・エラーメッセージの初期化
  $errors = array();

  $isSignupOK = false;
  
  $base_url = "http://co-19-301.99sv-coco.com/kadai/kadai3/kadai3_1";
  $url_signup = $base_url . "/signup.php";

  $dbh = new PDO($dsn, $user, $password_db);
  $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  /* ------------------------------------     本登録までの処理     ------------------------------------- */
  if (empty($_GET)) {
    header("location: $url_signup");
    exit();
  } else {
    $urltoken = isset($_GET["urltoken"]) ? $_GET["urltoken"] : NULL;
    if ($urltoken == '') {
      $errors['urltoken'] = "トークンがありません。";
    } else {
      $query = 'SELECT * FROM user WHERE urltoken = \'' . $urltoken . '\'';
      $stmt = $dbh->query($query);

      // 表示処理
      $signup_id;
      while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        $id = $row["id"];
        $signup_id = $id;
        $user_id = $row["user_id"];
        $name = $row["name"];
        $password = $row["password"];
        $isTemporary = $row["isTemporary"];
        $time_temporary = $row["time_temporary"];
        $urltoken = $row["urltoken"];
        // echo  "id : " . $id . ", user_id : " . $user_id . ", name : " . $name . "<br/>";
        // echo  "password : " . $password . ", isTemporary :" . $isTemporary . ", time_temporary : " . $time_temporary . "<br/>";
        // echo "urltoken : " . $urltoken. "<br/>";
      }

      //  仮登録のフラグをfalseへ
      $query = 'UPDATE user SET isTemporary = :isTemporary, urltoken = :urltoken WHERE id = :signup_id';
      $stmt = $dbh->prepare($query);
      $stmt->execute(array(':isTemporary' => false, ':urltoken' => "Unnecessary", ':signup_id' => $signup_id));

      $query = 'SELECT * FROM user WHERE id =' . $signup_id;
      $stmt = $dbh->query($query);
      while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // $id = $row["id"];
        $user_id = $row["user_id"];
        $name = $row["name"];
        $password = $row["password"];
        $isTemporary = $row["isTemporary"];
        $time_temporary = $row["time_temporary"];
        // $urltoken = $row["urltoken"];
        // echo  "id : " . $id . ", user_id : " . $user_id . ", name : " . $name . "<br/>";
        // echo  "password : " . $password . ", isTemporary :" . $isTemporary . ", time_temporary : " . $time_temporary . "<br/>";
        // echo "urltoken : " . $urltoken. "<br/>";
      }

      $isSignupOK = true;
    
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
    <h1>簡易掲示板</h1>
  </div>

  <div class="container">
    <?php if ($isSignupOK): ?>
    <h1 class="page_title">本登録完了</h1>
    <div class="signup_info">
      <p>ようこそ「</p>
      <p class="emphasis"> <?php echo $name ?> </p>
      <p>」さん</p>
    </div>
    <div class="signup_info">
      <p>ログインID：</p>
      <p class="emphasis"> <?php echo $user_id ?> </p>
    </div>
    <div class="signup_info">
      <p>パスワード：</p>
      <p class="emphasis"> <?php echo $password ?> </p>
    </div>
    
    <p>ログインフォームは<a href=<?php echo $url_signup ?> >こちら</a></p>

    <?php endif; ?>
  
  </div>

  </body>
</html>