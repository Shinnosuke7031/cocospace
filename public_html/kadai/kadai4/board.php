<?php

require("../define.php");
require("../funk.php");

// cache
require_once("Cache/Lite.php");
$cacheoptions = array(
  "cacheDir" => "cache/",
  "lifeTime" => 1800,
  "automaticCleaningFactor" => "100",
);
$cache = new Cache_Lite($cacheoptions);
$cacheid_counts="cache_counts";
$cacheid_ids="cache_ids";
$cacheid_names="cache_names";
$cacheid_times="cache_times";
$cacheid_comments="cache_comments";
$cacheid_types="cache_types";
$cacheid_fnames="cache_fnames";

// smartyの設定ファイル読み込み
require_once(realpath(__DIR__) . "/smarty/Autoloader.php");
require_once(realpath(__DIR__) . '/smarty/SmartyBC.class.php');
Smarty_Autoloader::register();

$obj = new StdClass();
$urls = new StdClass();//url変数用オブジェクト
$db = new StdClass();//DB用オブジェクト
// $display = new StdClass();//表示用オブジェクト
$smarty = new Smarty();

$url_data = URLs();

$urls->base_url = $url_data["base"];
$urls->top_url = $url_data["top"];
$urls->url_signup = $url_data["signup"];
$urls->board_url = $url_data["board"];
$urls->logout_url = $url_data["logout"];
$urls->import_url = $url_data["import"];

$DBinfo = DBinfo();
$db->dbh = $DBinfo["dsn"];
$db->dbh = $DBinfo["user"];
$db->dbh = $DBinfo["password"];

session_start();
  
$obj->user_id = '';
$obj->user_name = '';
// $obj->comment_form = "";

if (!isset($_SESSION['user_id'])) {
  header("location: $urls->top_url");
} else {
  $obj->user_id = $_SESSION["user_id"];
  $_SESSION["view_count"] += 1;
}

$dbh = new PDO($DBinfo["dsn"], $DBinfo["user"], $DBinfo["password"]);
$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$query = 'SELECT * FROM user WHERE user_id = \'' . $obj->user_id . '\'';
$stmt = $dbh->query($query);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$obj->user_name = $row["name"];

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
        $_SESSION["view_count"] = 0;
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
        $dbh = new PDO($DBinfo["dsn"], $DBinfo["user"], $DBinfo["password"]);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // クエリの作成(INSERT)
        $query = 'INSERT INTO posts(user_id, name, comment, isFile, fname, extension, time, raw_data) 
                            VALUES(:user_id, :name, :comment, :isFile, :fname, :extension, :time, :raw_data)';
        $stmt = $dbh->prepare($query);
            
        $stmt -> bindValue(':user_id', $obj->user_id, PDO::PARAM_STR);
        $stmt -> bindValue(':name', $obj->user_name, PDO::PARAM_STR);
        $stmt -> bindValue(':comment', $comment, PDO::PARAM_STR);
        $stmt -> bindValue(':isFile', $isFile, PDO::PARAM_INT);
        $stmt -> bindValue(':fname', $fname, PDO::PARAM_STR);
        $stmt -> bindValue(':extension', $extension, PDO::PARAM_STR);
        $stmt -> bindValue(':time', $time, PDO::PARAM_STR);
        $stmt -> bindValue(':raw_data', "", PDO::PARAM_STR);
                  
        // 実行
        $stmt->execute();
        $_SESSION["view_count"] = 0;
          
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
    // if (isset($_POST["up_sort2"])) {
    //   if ($_POST["up_sort2"] == "昇順ソート") {
    //     $query = 'SELECT * FROM posts';
    //   }
    // }
    // if (isset($_POST["down_sort2"])) {
    //   if ($_POST["down_sort2"] == "降順ソート") {
    //     $query = 'SELECT * FROM posts ORDER BY id DESC';
    //   }
    // }
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

    if ($delete_user_id == $obj->user_id) { // ログイン中のuser_idと削除対象コメントのuser_idが一致した場合のみ削除できる
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
  $_SESSION["view_count"] = 0;
}
  
/////////////////// 指定された番号のコメントを表示(edit) ////////////////
if (isset($_POST["edit_number"]) && isset($_POST["password_edit"])) {
    
  $password_edit = $_POST["password_edit"];
  $password_edit_check = "";
  
  $edit_number = $_POST["edit_number"];
  
  try {
    // 入力された番号に対応したidを見つける
    $query = 'SELECT * FROM posts';
    // if (isset($_POST["up_sort2"])) {
    //   if ($_POST["up_sort2"] == "昇順ソート") {
    //     $query = 'SELECT * FROM posts';
    //   }
    // }
    // if (isset($_POST["down_sort2"])) {
    //   if ($_POST["down_sort2"] == "降順ソート") {
    //     $query = 'SELECT * FROM posts ORDER BY id DESC';
    //   }
    // }
    $stmt = $dbh->query($query);
    $count = 0;
    $edit_id = 0;
    $isTempFile = true;
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
      $count++;
      if ($count == $edit_number) {
        $edit_id = $row["id"];
        $edit_user_id = $row["user_id"];
        $isTempFile = $row["isFile"];
      }
    }

    if ($edit_user_id == $obj->user_id && !$isTempFile) {
      $query = 'SELECT * FROM user WHERE user_id = \'' . $edit_user_id . '\'';
      $stmt = $dbh->query($query);
    
      while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        $password_edit_check = $row["password"];
      }

      $query = 'SELECT * FROM posts WHERE id = ' . $edit_id;
      $stmt = $dbh->query($query);

      while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $obj->comment_form = $row["comment"];
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
  $_SESSION["view_count"] = 0;
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
    echo ("<a href=\"". $urls->top_url ."\">戻る</a><br/>");
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
  $stmt -> bindValue(':user_id', $obj->user_id, PDO::PARAM_STR);
  $stmt -> bindValue(':name', $obj->user_name, PDO::PARAM_STR);
  $stmt -> bindValue(':comment', "", PDO::PARAM_STR);
  $stmt -> bindValue(':isFile', $isFile, PDO::PARAM_INT);
  $stmt -> bindValue(':fname', $fname, PDO::PARAM_STR);
  $stmt -> bindValue(':extension', $extension, PDO::PARAM_STR);
  $stmt -> bindValue(':time', $time, PDO::PARAM_STR);
  $stmt -> bindValue(":raw_data",$raw_data, PDO::PARAM_STR);      
  // 実行
  $stmt->execute();
  $_SESSION["view_count"] = 0;
}

/* 表示部分 */
$counts = array();
$ids = array();
$names = array();
$comments = array();
$times = array();
$types = array();
$fnames = array();

if ($jsonCounts = $cache->get($cacheid_counts) && $_SESSION["view_count"] > 1) {//キャッシュあり

  $jsonIDs = $cache->get($cacheid_ids);
  $jsonNames = $cache->get($cacheid_names);
  $jsonTimes = $cache->get($cacheid_times);
  $jsonComments = $cache->get($cacheid_comments);
  $jsonTypes = $cache->get($cacheid_types);
  $jsonFnames = $cache->get($cacheid_fnames);

  $counts = json_decode($jsonCounts, true);
  $ids = json_decode($jsonIDs, true);
  $names = json_decode($jsonNames, true);
  $times = json_decode($jsonTimes, true);
  $comments = json_decode($jsonComments, true);
  $types = json_decode($jsonTypes, true);
  $fnames = json_decode($jsonFnames, true);

  // echo "<br/>";
  // print_r($counts);
  // echo "<br/>";
  // print_r($names);
  // echo "<br/>";
  // print_r($ids);
  // echo "<br/>";
  // print_r($times);
  // echo "<br/>";
  // print_r($comments);
  // echo "<br/>";
  // print_r($types);
  // echo "<br/>";
  // print_r($fnames);
  // echo "キャッシュあり";

} else {//キャッシュなし

  $count = 1;
  try{
    $dbh = new PDO($DBinfo["dsn"], $DBinfo["user"], $DBinfo["password"]);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $import_url = $urls->import_url;
    // クエリの実行(SELECT)
    /////////////////// 昇順降順切替 ////////////////
    $query = 'SELECT * FROM posts';
    // if (isset($_POST["up_sort"])) {
    //   if ($_POST["up_sort"] == "昇順ソート") {
    //     $query = 'SELECT * FROM posts';
    //   }
    // }
    // if (isset($_POST["down_sort"])) {
    //   if ($_POST["down_sort"] == "降順ソート") {
    //     $query = 'SELECT * FROM posts ORDER BY id DESC';
    //   }
    // }
    
    $stmt = $dbh->query($query);
    // 表示処理
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
      array_push($counts, $count);
      array_push($ids, $row["id"]);
      array_push($names, $row["name"]);
      array_push($times, $row["time"]);
      if ($row["isFile"]) {
        array_push($comments, "none");
        array_push($types, $row["extension"]);
        array_push($fnames, $row["fname"]);
        // array_push($raw_data, "raw_data");
      } else {
        array_push($comments, $row["comment"]);
        array_push($types, "text");
        array_push($fnames, "none");
      }
      $count++;
    }
  }catch(PDOException $e){
    print("データベースの接続に失敗しました");
    die();
  }
  $jsonCounts = json_encode($counts);
  $cache->save($jsonCounts, $cacheid_counts);
  $jsonIDs = json_encode($ids);
  $cache->save($jsonIDs, $cacheid_ids);
  $jsonNames = json_encode($names);
  $cache->save($jsonNames, $cacheid_names);
  $jsonTimes = json_encode($times);
  $cache->save($jsonTimes, $cacheid_times);
  $jsonComments = json_encode($comments);
  $cache->save($jsonComments, $cacheid_comments);
  $jsonTypes = json_encode($types);
  $cache->save($jsonTypes, $cacheid_types);
  $jsonFnames = json_encode($fnames);
  $cache->save($jsonFnames, $cacheid_fnames);
}
  
// foreach ($counts as $key => $count) {
//   echo "[" . $key . "] => count : " . $count . "<br/>";
//   echo "[" . $key . "] => ids : " . $ids[$key] . "<br/>";
//   echo "[" . $key . "] => names : " . $names[$key] . "<br/>";
//   echo "[" . $key . "] => times : " . $times[$key] . "<br/>";
//   echo "[" . $key . "] => comments : " . $comments[$key] . "<br/>";
//   echo "[" . $key . "] => types : " . $types[$key] . "<br/>";
//   echo "[" . $key . "] => raw_data : " . $raw_data[$key] . "<br/>";
// }
$obj->sort = "up";
if (isset($_POST["up_sort"])) {
  if ($_POST["up_sort"] == "昇順ソート") {
    $obj->sort = "up";
  }
}
if (isset($_POST["down_sort"])) {
  if ($_POST["down_sort"] == "降順ソート") {
    $obj->sort = "down";
  }
}

$dbh = null;

$obj->isEditMode = $isEditMode;
$obj->edit_id = $edit_id;

/* ユーザーエージェント */
$ua = $_SERVER["HTTP_USER_AGENT"];
if((strpos($ua,"Android") !== false) && (strpos($ua,"Mobile") !== false) || (strpos($ua,"iPhone") !== false ) || (strpos($ua,"Windows Phone") !== false)){
	$obj->user_agent="smartphone";
}elseif((strpos($ua,"DoCoMo") !== false) || (strpos($ua,"KDDI") !== false) || (strpos($ua,"SoftBank") !== false)|| (strpos($ua,"vodafone") !== false) || (strpos($ua,"J PHONE") !== false)){
	$obj->user_agent="mobile";
}else{
	$obj->user_agent="PC";
}
/*-------------------*/

$smarty->php_handling = Smarty::PHP_ALLOW;
$smarty->assign('obj', $obj);
$smarty->assign('urls', $urls);
$smarty->assign('db', $db);
$smarty->assign('counts', $counts);
$smarty->assign('ids', $ids);
$smarty->assign('names', $names);
$smarty->assign('comments', $comments);
$smarty->assign('times', $times);
$smarty->assign('types', $types);
$smarty->assign('fnames', $fnames);

$smarty->display('board.tpl');


// function cache() {
//   /* キャッシュ */
//   require_once("Cache/Lite.php");
//   $cacheoptions = array(
//     "cacheDir" => "cache/",
//     "lifeTime" => 1800,
//     "automaticCleaningFactor" => "100",
//   );
//   $cache = new Cache_Lite($cacheoptions);
//   //Cacheするデータを見分ける一意的な名前
//   $cache_id="cache_id01";
//   if ($cacheData = $cache->get($cache_id)) {
//     //Cacheしたファイルがあるとき
//     // $buff=$cacheData;
//     echo "キャッシュ有効";
//   }else{
//     //Cacheしたファイルがないとき
//     // $savedata=file_get_contents("cache_files/display.php");
//     $savedata=file_get_contents("cache_files/display.php");
//     // $buff=$savedata;
//     $cache->save($savedata, $cache_id);
//     echo "キャッシュなし";
//   }
//   /*************/
//   // print_r($buff);
// }