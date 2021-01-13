<?php
  require("../define.php");
  require("../funk.php");
  
  // smartyの設定ファイル読み込み
  require_once(realpath(__DIR__) . "/smarty/Autoloader.php");
  Smarty_Autoloader::register();
  
  $obj = new StdClass();
  $urls = new StdClass();//url変数用オブジェクト
  $smarty = new Smarty();
  
  $url_data = URLs();
  
  $urls->base_url = $url_data["base"];
  $urls->top_url = $url_data["top"];
  $urls->url_signup = $url_data["signup"];
  $urls->board_url = $url_data["board"];
  $urls->logout_url = $url_data["logout"];
  $url_signup_complete = $urls->base_url . "/signup_complete.php";
  
  $DBinfo = DBinfo();
  $dsn = $DBinfo["dsn"];
  $user = $DBinfo["user"];
  $password_db = $DBinfo["password"];

  //成功・エラーメッセージの初期化
  $errors = array();

  $obj->isSignupOK = false;
  
  // $base_url = "http://co-19-301.99sv-coco.com/kadai/kadai4";
  // $top_url = $base_url . "/";
  // $url_signup = $base_url . "/signup.php";

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
        $obj->user_id = $row["user_id"];
        $name = $row["name"];
        $obj->name = $row["name"];
        $password = $row["password"];
        $obj->password = $row["password"];
        $isTemporary = $row["isTemporary"];
        $time_temporary = $row["time_temporary"];
        // $urltoken = $row["urltoken"];
        // echo  "id : " . $id . ", user_id : " . $user_id . ", name : " . $name . "<br/>";
        // echo  "password : " . $password . ", isTemporary :" . $isTemporary . ", time_temporary : " . $time_temporary . "<br/>";
        // echo "urltoken : " . $urltoken. "<br/>";
      }

      $obj->isSignupOK = true;
    
    }

  }
  
  /* -------------------------------------------------------------------------------------------- */
  $dbh = null;

  /* ユーザーエージェント */
  $ua = $_SERVER["HTTP_USER_AGENT"];
  $obj->user_agent="PC";
  if((strpos($ua,"Android") !== false) && (strpos($ua,"Mobile") !== false) || (strpos($ua,"iPhone") !== false ) || (strpos($ua,"Windows Phone") !== false)){
  	$obj->user_agent="smartphone";
  }elseif((strpos($ua,"DoCoMo") !== false) || (strpos($ua,"KDDI") !== false) || (strpos($ua,"SoftBank") !== false)|| (strpos($ua,"vodafone") !== false) || (strpos($ua,"J PHONE") !== false)){
  	$obj->user_agent="mobile";
  }else{
  	$obj->user_agent="PC";
  }
  /*-------------------*/

  $smarty->assign('obj', $obj);
  $smarty->assign('urls', $urls);
  $smarty->display('signup_complete.tpl');
?>

