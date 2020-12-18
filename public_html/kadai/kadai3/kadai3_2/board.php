<?php
  $base_url = "http://co-19-301.99sv-coco.com/kadai/kadai3/kadai3_2";
  $top_url = $base_url . "/";
  $url_signup = $base_url . "/signup.php";
  $board_url = $base_url . "/board.php";
  $logout_url = $base_url . "/logout.php";

  /*--------- DB information -----------*/
  $dsn      = 'mysql:dbname=co_19_301_99sv_coco_com;host=localhost';
  $user     = 'co-19-301.99sv-coco_c';
  $password_db = 'Em4kxvSU';
  /*------------------------------------*/

  require("../funk.php");

  session_start();
  
  $user_id = '';
  $password = '';
  $user_name = '';

  if (!isset($_SESSION['user_id'])) {
    header("location: $top_url");
  } else {
    $user_id = $_SESSION["user_id"];
  }

  $dbh = new PDO($dsn, $user, $password_db);
  $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $query = 'SELECT * FROM user WHERE user_id = \'' . $user_id . '\'';
  $stmt = $dbh->query($query);
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  $user_name = $row["name"];

  $isEditMode = 0;
  $edit_id = 0;
  /////////////////// 入力された名前とコメントのデータを取得 ////////////////
  if (isset($_POST["comment"])) {
    if($_POST["comment"] != "") {
  
      $_normalAddMode = true;
      $edit_id = $_POST["edit_id"];
      
      // if ($edit_id) {
      //   $time = date("Y-m-d H:i:s");
      //   $name = $_POST["name"];
      //   $comment = $_POST["comment"];
      //   // $password = $_POST["password"];
  
      //   try {
      //     // クエリの作成(UPDATE)
      //     $query = 'UPDATE kadai2_MySQL_TEST SET name = :name, comment = :comment, time = :time, password = :password WHERE id = :id';
      //     $stmt = $dbh->prepare($query);
      //     // 実行
      //     $stmt->execute(array(':name' => $name, ':comment' => $comment, ':time' => $time, ':password' => $password, ':id' => $edit_id));
      //   }catch(PDOException $e){
      //     print("データベースの接続に失敗しました");
      //     die();
      //   }
        
      //   $_normalAddMode = false;
      //   $isEditMode = 0;
      // }
      
      if ($_normalAddMode) {
        $time = now();
        $comment = $_POST["comment"];
        $isFile = 0;
        $fname = "";
        $extension = "";

        try{
          $dbh = new PDO($dsn, $user, $password_db);
          $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          // クエリの作成(INSERT)
          $query = 'INSERT INTO posts(user_id, name, comment, isFile, fname, extension, time) 
                              VALUES(:user_id, :name, :comment, :isFile, :fname, :extension, :time)';
          $stmt = $dbh->prepare($query);
            
          $stmt -> bindValue(':user_id', $user_id, PDO::PARAM_STR_CHAR);
          $stmt -> bindValue(':name', $user_name, PDO::PARAM_STR_CHAR);
          $stmt -> bindValue(':comment', $comment, PDO::PARAM_STR_CHAR);
          $stmt -> bindValue(':isFile', $isFile, PDO::PARAM_INT);
          $stmt -> bindValue(':fname', $fname, PDO::PARAM_STR_CHAR);
          $stmt -> bindValue(':extension', $extension, PDO::PARAM_STR_CHAR);
          $stmt -> bindValue(':time', $time, PDO::PARAM_STR_CHAR);
                  
          // 実行
          $stmt->execute();
          
        }catch(PDOException $e){
          print("データベースの接続に失敗しました".$e->getMessage());
          die();
        }
      }
      
    }
  
  }
  
  /////////////////// 指定された番号のコメントを削除 ////////////////
  if (isset($_POST["delete_number"]) && isset($_POST["password_delete"])) {
  
    $delete_number = $_POST["delete_number"];
  
    $password_delete = $_POST["password_delete"];
    $password_delete_check = "";
    try {
      // 入力された番号に対応したidを見つける
      $query = 'SELECT * FROM kadai2_MySQL_TEST';
      $stmt = $dbh->query($query);
      $count = 0;
      $delete_id = 0;
      while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        $count++;
        $id = $row["id"];
        if ($delete_number == $count) $delete_id = $id;
      }
  
      // クエリの実行(SELECT)
      $query = 'SELECT * FROM kadai2_MySQL_TEST WHERE id =' . $delete_id;
      $stmt = $dbh->query($query);
      // チェック用のPassword
      while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        $password_delete_check = $row["password"];
      }
    
      if ($password_delete == $password_delete_check) {
        // クエリの作成(DELETE)
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = 'DELETE FROM kadai2_MySQL_TEST WHERE id = :id';
        $stmt = $dbh->prepare($query);
        // 実行
        $stmt->execute(array(':id' => $delete_id));
  
      }
      else if ($password_delete_check == "") echo "<script type='text/javascript'>alert('対象のコメントがありません。');</script>"; 
      else echo "<script type='text/javascript'>alert('パスワードが違います。');</script>"; 
  
    }catch(PDOException $e){
      print("データベースの接続に失敗しました");
      die();
    }
  }
  
  /////////////////// 指定された番号のコメントを表示(edit) ////////////////
  if (isset($_POST["edit_number"]) && isset($_POST["password_edit"])) {
    
    $password_edit = $_POST["password_edit"];
    $password_edit_check = "";
  
    $edit_number = $_POST["edit_number"];
  
    try {
      // 入力された番号に対応したidを見つける
      $query = 'SELECT * FROM kadai2_MySQL_TEST';
      $stmt = $dbh->query($query);
      $count = 0;
      $edit_id = 0;
      while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        $count++;
        $id = $row["id"];
        if ($count == $edit_number) $edit_id = $id;
      }
  
      $query = 'SELECT * FROM kadai2_MySQL_TEST WHERE id = ' . $edit_id;
      $stmt = $dbh->query($query);
    
      while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        $name_form = $row["name"];
        $comment_form = $row["comment"];
        $password_form = $row["password"];
        $password_edit_check = $row["password"];
      }
       
      if ($password_edit == $password_edit_check) $isEditMode = $edit_id;
      else if ($password_delete_check == "") echo "<script type='text/javascript'>alert('対象のコメントがありません。');</script>"; 
      else echo "<script type='text/javascript'>alert('パスワードが違います。');</script>";
  
    }catch(PDOException $e){
      print("データベースの接続に失敗しました");
      die();
    }
  
  }

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
    
    <p> ようこそ： <?php echo $user_name ?> さん </p>

    <h1 class="page_title">掲示板</h1>

    <form action="board.php" method="post">
      <input type="hidden" value=<?php echo $isEditMode; ?> name="JugeEditMode_inInputForm">
      <input type="hidden" value=<?php echo $edit_id; ?> name="edit_id" >
      <div class="form-element set_btn">
        <p>コメント：</p>
        <input type="text" name="comment" value=<?php
          if ($isEditMode) echo $comment_form;
          else echo "";
        ?> >
        <button class="btn-submit" type="submit">投稿</button>
      </div>
      <div class="comment_lineup">
        <?php 
          echo '<div class="info ex">';
          echo '<p class="main_info">ID : id, <span style="font-weight: bold;"> 名前 </span><span style="font-weight: bold;">「コメント」</span> </p><p class="time_info">時間</p>';
          echo "</div>";
          $count = 1;
          try{
            $dbh = new PDO($dsn, $user, $password_db);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // クエリの実行(SELECT)
            $query = 'SELECT * FROM posts';
            $stmt = $dbh->query($query);          
            // 表示処理
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
              $id = $row["id"];
              $name = $row["name"];
              $comment = $row["comment"];
              $time = $row["time"];
      
              echo '<div class="info">';
              echo '<p class="main_info">ID : ' . $count . ', <span style="font-weight: bold;"> ' . $name . "</span>" . '<span style="font-weight: bold;">「' . $comment . '」</span> </p><p class="time_info">' . $time."</p>";
              echo "</div>";
              $count++;
            }
          
          }catch(PDOException $e){
            print("データベースの接続に失敗しました");
            die();
          }
  
          $dbh = null;
        ?>
      </div>
    </form>

  <form class="form_mini" action="board.php" method="post" onsubmit="return check()">
    <div class="form-element">
      <p>削除番号：</p><input type="number" name="delete_number">
    </div>
    <div class="form-element set_btn">
      <p>パスワード：</p>
      <input type="password" name="password_delete">
      <button class="btn-submit" type="submit">削除</button>
    </div>
  </form>
  <form class="form_mini" action="kadai2_advanced_level.php" method="post">
    <div class="form-element">
      <input type="hidden" value=<?php echo $isEditMode; ?> name="JugeEditMode">
      <p>編集番号：</p><input type="number" name="edit_number">
    </div>
    <div class="form-element set_btn">
      <p>パスワード：</p>
      <input type="password" name="password_edit">
      <button class="btn-submit" type="submit">番号を指定</button>
    </div>
  </form>

    <form action=<?php echo $logout_url ?> method="POST" >
      <button>ログアウト</button>
    </form>
  
  </div>
  </body>
</html>