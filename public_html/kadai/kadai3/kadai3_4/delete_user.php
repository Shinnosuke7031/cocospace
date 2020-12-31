<?php
  // require("../funk.php");
  // require("./define.php");

  /*--------- DB information -----------*/
function DBinfo () {
  $dsn      = 'mysql:dbname=co_19_301_99sv_coco_com;host=localhost';
  $user     = 'co-19-301.99sv-coco_c';
  $password_db = 'Em4kxvSU';
  
  $array = array("dsn" => $dsn, "user" => $user, "password" => $password_db);
  return $array;
}
/*------------------------------------*/

function from_email_address () {
  return "sengoku731sin@gmail.com";
}

  $DBinfo = DBinfo();
  $dsn = $DBinfo["dsn"];
  $user = $DBinfo["user"];
  $password_db = $DBinfo["password"];

  function now() {
    return date("Y-m-d H:i:s");
  }

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
  

  try {
    $dbh = new PDO($dsn, $user, $password_db);
    $query = "SELECT * FROM user";
    $stmt = $dbh->query($query);
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
      $id = $row["id"];
      $user_id = $row["user_id"];
      $name = $row["name"];
      $password = $row["password"];
      $isTemporary = $row["isTemporary"];
      $time_temporary = $row["time_temporary"];
      $urltoken = $row["urltoken"];
      if ($isTemporary != 0) {
        $diffTimeOutPut = array();
        $diffTimeOutPut = time_diff($time_temporary, now());
        // echo $diffTimeOutPut['hours'].'時間<br/>';
        // echo $diffTimeOutPut['minutes'].'分<br/>';
        // echo $diffTimeOutPut['seconds'].'秒<br/>';
        if ($diffTimeOutPut['hours']>=24) {
          // echo '時間オーバーで削除<br/>';
          $query = 'DELETE FROM user WHERE id = :id';
          $stmt = $dbh->prepare($query);
          // 実行
          $stmt->execute(array(':id' => $id));
        }
      }
    }
  }
  catch (PDOException $e) {
    exit($e->getMessage());
  }
?>