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


$obj->alert_name=$alert_name;
$obj->alert_to=$alert_to;
$obj->alert_pass1=$alert_pass1;
$obj->alert_pass2=$alert_pass2;
$obj->input_name=$input_name;
$obj->input_to=$input_to;
$obj->input_password=$input_password;
$obj->isFormComplete=$isFormComplete;

$smarty->assign('obj', $obj);
$smarty->assign('urls', $urls);
$smarty->display('signup.tpl');

?>
