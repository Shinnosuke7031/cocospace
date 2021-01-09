<?php
require("../define.php");
require("../funk.php");

// smartyの設定ファイル読み込み
require_once(realpath(__DIR__) . "/smarty/Autoloader.php");
Smarty_Autoloader::register();

$obj = new StdClass();
$urls = new StdClass();//url変数用オブジェクト
$smarty = new Smarty();

$base_url = "http://co-19-301.99sv-coco.com/kadai/kadai4";
$top_url = $base_url . "/";
$url_signup = $base_url . "/signup.php";
$board_url = $base_url . "/board.php";

$urls->base_url = $base_url;
$urls->top_url = $top_url;
$urls->url_signup = $url_signup;
$urls->board_url = $board_url;

session_start();
if (isset($_SESSION['user_id'])) {
  header("location: $board_url");
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
      header("location: $board_url");
      exit();
    } else {
      $alert_pass1 = "IDまたはパスワードが違います";
    }
  }

}
/* -------------------------------------------------------------------------------------------- */

$dbh = null;

$obj->input_id = $input_id;
$obj->alert_pass1 = $alert_pass1;

$smarty->assign('obj', $obj);
$smarty->assign('urls', $urls);
$smarty->display('index.tpl');

?>