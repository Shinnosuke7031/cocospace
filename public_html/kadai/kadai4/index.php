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

session_start();
if (isset($_SESSION['user_id'])) {
  header("location: $urls->board_url");
}


$input_id = "";
$alert_pass1 = "";

$DBinfo = DBinfo();
$dbh = new PDO($DBinfo["dsn"], $DBinfo["user"], $DBinfo["password"]);
$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

/* ------------------------------------     ログイン処理     ------------------------------------- */
if (isset($_POST["input_id"]) && isset($_POST["input_password"])) {
  $input_id = $_POST["input_id"];
  $input_password = $_POST["input_password"];

  $query = 'SELECT * FROM user WHERE user_id = \'' . $input_id . '\'';
  $stmt = $dbh->query($query);

  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  if ($row["isTemporary"]) {//仮登録の場合
    $alert_pass1 = "このユーザーは仮登録のためログインできません";
  } else {
    if ($input_password != "" && $row["password"] == $input_password) {
      $_SESSION["user_id"] = $input_id;
      header("location: $urls->board_url");
      exit();
    } else {
      $alert_pass1 = "IDまたはパスワードが違います";
    }
  }

}
/* -------------------------------------------------------------------------------------------- */

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

$dbh = null;

$obj->input_id = $input_id;
$obj->alert_pass1 = $alert_pass1;

$smarty->assign('obj', $obj);
$smarty->assign('urls', $urls);
$smarty->display('index.tpl');

?>