<?php

require("../define.php");
require("../funk.php");

// smartyの設定ファイル読み込み
require_once(realpath(__DIR__) . "/smarty/Autoloader.php");
Smarty_Autoloader::register();

$smarty = new Smarty();
$urls = new StdClass();//url変数用オブジェクト

$url_data = URLs();

$urls->base_url = $url_data["base"];
$urls->top_url = $url_data["top"];
$urls->url_signup = $url_data["signup"];
$urls->board_url = $url_data["board"];
$urls->logout_url = $url_data["logout"];

session_start();
$_SESSION = array();//セッションの中身をすべて削除
session_destroy();//セッションを破壊

$smarty->assign('urls', $urls);
$smarty->display('logout.tpl');