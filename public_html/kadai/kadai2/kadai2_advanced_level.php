<?php
//http://co-19-301.99sv-coco.com/kadai/kadai2/kadai2_advanced_level.php
$dsn      = 'mysql:dbname=co_19_301_99sv_coco_com;host=localhost';
$user     = 'co-19-301.99sv-coco_c';
$password_db = 'Em4kxvSU';

try{
  $dbh = new PDO($dsn, $user, $password_db);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

}catch(PDOException $e){
  print("データベースの接続に失敗しました".$e->getMessage());
  die();
}

$isEditMode = 0;
$edit_id = 0;
/////////////////// 入力された名前とコメントのデータを取得 ////////////////
if (isset($_POST["name"]) && isset($_POST["comment"])) {
  if($_POST["name"] != "" && $_POST["comment"] != "") {

    $_normalAddMode = true;
    $edit_id = $_POST["edit_id"];
    
    if ($edit_id) {
      $time = date("Y-m-d H:i:s");
      $name = $_POST["name"];
      $comment = $_POST["comment"];
      $password = $_POST["password"];

      try {
        // クエリの作成(UPDATE)
        $query = 'UPDATE kadai2_MySQL_TEST SET name = :name, comment = :comment, time = :time, password = :password WHERE id = :id';
        $stmt = $dbh->prepare($query);
        // 実行
        $stmt->execute(array(':name' => $name, ':comment' => $comment, ':time' => $time, ':password' => $password, ':id' => $edit_id));
      }catch(PDOException $e){
        print("データベースの接続に失敗しました");
        die();
      }
      
      $_normalAddMode = false;
      $isEditMode = 0;
    }
    
    if ($_normalAddMode) {
      if($_POST["password"] != "") {

        // $fp = fopen("input_data_with_password.txt", "a+");
        
        $time = date("Y-m-d H:i:s");
        $name = $_POST["name"];
        $comment = $_POST["comment"];
        $password = $_POST["password"];
        try{
          $dbh = new PDO($dsn, $user, $password_db);
          $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          // クエリの作成(INSERT)
          $query = 'INSERT INTO kadai2_MySQL_TEST(name, comment, time, password) VALUES(:name, :comment, :time, :password)';
          $stmt = $dbh->prepare($query);
                
          $stmt -> bindValue(':name', $name, PDO::PARAM_STR_CHAR);
          $stmt -> bindValue(':comment', $comment, PDO::PARAM_STR_CHAR);
          $stmt -> bindValue(':time', (string)$time, PDO::PARAM_STR_CHAR);
          $stmt -> bindValue(':password', $password, PDO::PARAM_STR_CHAR);
                
          // 実行
          $stmt->execute();
        
        }catch(PDOException $e){
          print("データベースの接続に失敗しました");
          die();
        }
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

// 接続を閉じる
$dbh = null;
?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UFT-8">
    <title>簡易掲示板</title>
    <link rel="stylesheet" type="text/css" href="index.css">
    <script type="text/javascript">
      function check(){
      	if(confirm('削除してよろしいですか？')){
      		return true;
      	}
      	else{
      		alert('キャンセルされました');
      		return false;
      	}
      }
    </script>
  </head>
<body>
  <div class="container">

    <h1>簡易掲示板</h1>
    <form action="kadai2_advanced_level.php" method="post">
      <input type="hidden" value=<?php echo $isEditMode; ?> name="JugeEditMode_inInputForm">
      <input type="hidden" value=<?php echo $edit_id; ?> name="edit_id" >
      <div class="form-element">
        <p>名前：</p>
        <input type="text" name="name" value=<?php
          if ($isEditMode) echo $name_form;
          else echo "";
        ?> >
      </div>
      <div class="form-element">
        <p>コメント：</p>
        <input type="text" name="comment" value=<?php
          if ($isEditMode) echo $comment_form;
          else echo "";
        ?> >
      </div>
      <div class="form-element set_btn">
        <p>パスワード：</p>
        <input type="password" name="password" value=<?php
          if ($isEditMode) echo $password_form;
          else echo ""; ?> >
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
            $query = 'SELECT * FROM kadai2_MySQL_TEST';
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
    <form class="form_mini" action="kadai2_advanced_level.php" method="post" onsubmit="return check()">
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
  
  </div>
  </body>
</html>