<?php

function now() {
  return date("Y-m-d H:i:s");
}

function random($length)
{
    return substr(bin2hex(random_bytes($length)), 0, $length);
}

function h($s){
  return htmlspecialchars($s, ENT_QUOTES, 'utf-8');
}

//時分秒の差を返す関数
function time_diff($d1, $d2){ 
  //初期化
  $diffTime = array();  
  //タイムスタンプ
  $timeStamp1 = strtotime($d1);
  $timeStamp2 = strtotime($d2);  
  //タイムスタンプの差を計算
  $difSeconds = $timeStamp2 - $timeStamp1;  
  //秒の差を取得
  $diffTime['seconds'] = $difSeconds % 60;  
  //分の差を取得
  $difMinutes = ($difSeconds - ($difSeconds % 60)) / 60;
  $diffTime['minutes'] = $difMinutes % 60;  
  //時の差を取得
  $difHours = ($difMinutes - ($difMinutes % 60)) / 60;
  $diffTime['hours'] = $difHours;  
  //結果を返す
  return $diffTime;
 }

?>