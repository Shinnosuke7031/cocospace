<?php
  require("../funk.php");
  require("./define.php");
  $base_url = "http://co-19-301.99sv-coco.com/kadai/kadai3/kadai3_4";
  $top_url = $base_url . "/";
  $url_signup = $base_url . "/signup.php";
  $board_url = $base_url . "/board.php";
  $logout_url = $base_url . "/logout.php";
  $import_url = $base_url . "/import_media.php";

  $DBinfo = DBinfo();
  $dsn = $DBinfo["dsn"];
  $user = $DBinfo["user"];
  $password_db = $DBinfo["password"];

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
      
      if ($edit_id) {
        $time = now();
        $comment = $_POST["comment"];
  
        try {
          // クエリの作成(UPDATE)
          $query = 'UPDATE posts SET comment = :comment, time = :time WHERE id = :id';
          $stmt = $dbh->prepare($query);
          // 実行
          $stmt->execute(array(':comment' => $comment, ':time' => $time, ':id' => $edit_id));
        }catch(PDOException $e){
          print("データベースの接続に失敗しました");
          die();
        }
        
        $_normalAddMode = false;
        $isEditMode = 0;
      }
      
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
          $query = 'INSERT INTO posts(user_id, name, comment, isFile, fname, extension, time, raw_data) 
                              VALUES(:user_id, :name, :comment, :isFile, :fname, :extension, :time, :raw_data)';
          $stmt = $dbh->prepare($query);
            
          $stmt -> bindValue(':user_id', $user_id, PDO::PARAM_STR);
          $stmt -> bindValue(':name', $user_name, PDO::PARAM_STR);
          $stmt -> bindValue(':comment', $comment, PDO::PARAM_STR);
          $stmt -> bindValue(':isFile', $isFile, PDO::PARAM_INT);
          $stmt -> bindValue(':fname', $fname, PDO::PARAM_STR);
          $stmt -> bindValue(':extension', $extension, PDO::PARAM_STR);
          $stmt -> bindValue(':time', $time, PDO::PARAM_STR);
          $stmt -> bindValue(':raw_data', "", PDO::PARAM_STR);
                  
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
      // $query = 'SELECT * FROM kadai2_MySQL_TEST';
      $query = 'SELECT * FROM posts';
      $stmt = $dbh->query($query);
      $count = 0;
      $delete_id = 0;
      $delete_user_id = "";
      while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        $count++;
        if ($delete_number == $count) {
          $delete_id = $row["id"];
          $delete_user_id = $row["user_id"];
        }
      }

      if ($delete_user_id == $user_id) { // ログイン中のuser_idと削除対象コメントのuser_idが一致した場合のみ削除できる
        // クエリの実行(SELECT)
        // $query = 'SELECT * FROM kadai2_MySQL_TEST WHERE id =' . $delete_id;
        $query = 'SELECT * FROM user WHERE user_id = \'' . $delete_user_id . '\'';
        $stmt = $dbh->query($query);
        // チェック用のPassword
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
          $password_delete_check = $row["password"];
        }
      
        if ($password_delete == $password_delete_check) {
          // クエリの作成(DELETE)
          $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          $query = 'DELETE FROM posts WHERE id = :id';
          $stmt = $dbh->prepare($query);
          // 実行
          $stmt->execute(array(':id' => $delete_id));
    
        }
        else if ($password_delete_check == "") echo "<script type='text/javascript'>alert('対象のコメントがありません。');</script>"; 
        else echo "<script type='text/javascript'>alert('パスワードが違います。');</script>"; 

      } else {
        echo "<script type='text/javascript'>alert('選択されたコメントは削除できません。');</script>"; 
      }
  
  
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
      $query = 'SELECT * FROM posts';
      $stmt = $dbh->query($query);
      $count = 0;
      $edit_id = 0;
      $delete_user_id = "";
      $isTempFile = true;
      while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        $count++;
        if ($count == $edit_number) {
          $edit_id = $row["id"];
          $edit_user_id = $row["user_id"];
          $isTempFile = $row["isFile"];
        }
      }

      if ($edit_user_id == $user_id && !$isTempFile) {
        $query = 'SELECT * FROM user WHERE user_id = \'' . $edit_user_id . '\'';
        $stmt = $dbh->query($query);
    
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
          $password_edit_check = $row["password"];
        }

        $query = 'SELECT * FROM posts WHERE id = ' . $edit_id;
        $stmt = $dbh->query($query);

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          $comment_form = $row["comment"];
        }
       
        if ($password_edit == $password_edit_check) $isEditMode = $edit_id;
        else if ($password_edit_check == "") echo "<script type='text/javascript'>alert('対象のコメントがありません。');</script>"; 
        else echo "<script type='text/javascript'>alert('パスワードが違います。');</script>";
      } else {
        echo "<script type='text/javascript'>alert('選択されたコメントは編集できません。');</script>"; 
      }

    }catch(PDOException $e){
      print("データベースの接続に失敗しました");
      die();
    }
  
  }

  /////////////////// 動画・画像アップロード ////////////////
  if (isset($_FILES['upfile']['error']) && is_int($_FILES['upfile']['error']) && $_FILES["upfile"]["name"] !== ""){
    //エラーチェック
    switch ($_FILES['upfile']['error']) {
      case UPLOAD_ERR_OK: // OK
          break;
      case UPLOAD_ERR_NO_FILE:   // 未選択
          throw new RuntimeException('ファイルが選択されていません', 400);
      case UPLOAD_ERR_INI_SIZE:  // php.ini定義の最大サイズ超過
          throw new RuntimeException('ファイルサイズが大きすぎます', 400);
      default:
          throw new RuntimeException('その他のエラーが発生しました', 500);
    }
    //画像・動画をバイナリデータにする
    $raw_data = file_get_contents($_FILES['upfile']['tmp_name']);

    //拡張子を見る
    $tmp = pathinfo($_FILES["upfile"]["name"]);
    $extension = $tmp["extension"];
    if($extension === "jpg" || $extension === "jpeg" || $extension === "JPG" || $extension === "JPEG"){
      $extension = "jpeg";
    }
    elseif($extension === "png" || $extension === "PNG"){
      $extension = "png";
    }
    elseif($extension === "gif" || $extension === "GIF"){
      $extension = "gif";
    }
    elseif($extension === "mp4" || $extension === "MP4"){
      $extension = "mp4";
    }
    else{
      echo "非対応ファイルです．<br/>";
      echo ("<a href=\"". $top_url ."\">戻る</a><br/>");
      exit(1);
    }
    //DBに格納するファイルネーム設定
    //サーバー側の一時的なファイルネームと取得時刻を結合した文字列にsha256をかける
    $date = getdate();//年月日時間をオブジェクトとして取得
    $time = now();
    $fname = $_FILES["upfile"]["tmp_name"].$date["year"].$date["mon"].$date["mday"].$date["hours"].$date["minutes"].$date["seconds"];
    $fname = hash("sha256", $fname);
    $isFile = 1;

    //画像・動画をDBに格納
    $query = 'INSERT INTO posts(user_id, name, comment, isFile, fname, extension, time, raw_data) 
                              VALUES(:user_id, :name, :comment, :isFile, :fname, :extension, :time, :raw_data)';
    $stmt = $dbh->prepare($query);
    $stmt -> bindValue(':user_id', $user_id, PDO::PARAM_STR);
    $stmt -> bindValue(':name', $user_name, PDO::PARAM_STR);
    $stmt -> bindValue(':comment', "", PDO::PARAM_STR);
    $stmt -> bindValue(':isFile', $isFile, PDO::PARAM_INT);
    $stmt -> bindValue(':fname', $fname, PDO::PARAM_STR);
    $stmt -> bindValue(':extension', $extension, PDO::PARAM_STR);
    $stmt -> bindValue(':time', $time, PDO::PARAM_STR);
    $stmt -> bindValue(":raw_data",$raw_data, PDO::PARAM_STR);      
    // 実行
    $stmt->execute();
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
    <p> ようこそ： <?php echo $user_name ?> さん </p>
    <h1><a href=<?php echo $top_url ?>>簡易掲示板</a></h1>
    <form class="logout_form" action=<?php echo $logout_url ?> method="POST" >
      <button class="logout_btn btn">ログアウト</button>
    </form>
  </div>

  <div class="container">

    <h1 class="page_title">掲示板</h1>

    <form class="form_sort_btn" action="board.php" method="post">
      <input class="btn_input_anime btn_input" type="submit" name="up_sort" value="昇順ソート">
      <input class="btn_input_anime btn_input" type="submit" name="down_sort" value="降順ソート">
    </form>

    <form class="form_file_upload" action="board.php" enctype="multipart/form-data" method="post">
      <div class="file_upload">
        <div class="form-element2">
          <label>画像/動画アップロード</label>
          <input class="btn_input2" type="file" name="upfile">
        </div>
        ※対応形式 : 画像(jpeg, png, gif), 動画(mp4)<br>
        <input class="btn_input_anime btn_input" type="submit" value="アップロード">
      </div>
    </form>

    <form action="board.php" method="post">
      <input type="hidden" value=<?php echo $isEditMode; ?> name="JugeEditMode_inInputForm">
      <input type="hidden" value=<?php echo $edit_id; ?> name="edit_id" >
      <div class="form-element set_btn submit_form">
        <p>コメント：</p>
        <input type="text" name="comment" value=<?php
          if ($isEditMode) echo $comment_form;
          else echo "";
        ?> >
        <button class="btn-submit btn" type="submit">投稿</button>
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
            /////////////////// 昇順降順切替 ////////////////
            $query = 'SELECT * FROM posts';
            if (isset($_POST["up_sort"])) {
              if ($_POST["up_sort"] == "昇順ソート") {
                $query = 'SELECT * FROM posts';
              }
            }
            if (isset($_POST["down_sort"])) {
              if ($_POST["down_sort"] == "降順ソート") {
                $query = 'SELECT * FROM posts ORDER BY id DESC';
              }
            }
            
            $stmt = $dbh->query($query);
            // 表示処理
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
              $id = $row["id"];
              $name = $row["name"];
              $comment = $row["comment"];
              $time = $row["time"];

              if ($row["isFile"]) {
                echo '<div class="info main_image">';
                echo '<p class="main_info">ID : ' . $count . ', <span style="font-weight: bold;"> ' . $name . "</span>";
                $target = $row["fname"];
                if($row["extension"] == "mp4") {
                  echo ("<video src=\"$import_url?target=$target\" height=\"70\" controls></video>");
                }
                elseif($row["extension"] == "jpeg" || $row["extension"] == "png" || $row["extension"] == "gif") {
                  echo ("<img class=\"images\" src='import_media.php?target=$target'>");
                }
                echo '</p><p class="time_info">' . $time."</p>";
                echo ("<p/>");
                echo "</div>";
              } else {
                echo '<div class="info">';
                echo '<p class="main_info">ID : ' . $count . ', <span style="font-weight: bold;"> ' . $name . "</span>" . '<span style="font-weight: bold;">「' . $comment . '」</span> </p><p class="time_info">' . $time."</p>";
                echo "</div>";
              }
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
      <button class="btn-submit btn" type="submit">削除</button>
    </div>
  </form>
  <form class="form_mini" action="board.php" method="post">
    <div class="form-element">
      <input type="hidden" value=<?php echo $isEditMode; ?> name="JugeEditMode">
      <p>編集番号：</p><input type="number" name="edit_number">
    </div>
    <div class="form-element set_btn">
      <p>パスワード：</p>
      <input type="password" name="password_edit">
      <button class="btn-submit btn" type="submit">番号を指定</button>
    </div>
  </form>

    <!-- <form action=<?php echo $logout_url ?> method="POST" >
      <button>ログアウト</button>
    </form> -->
  
  </div>
  </body>
</html>