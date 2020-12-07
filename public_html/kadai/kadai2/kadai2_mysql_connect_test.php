<?php
$dsn      = 'mysql:dbname=co_19_301_99sv_coco_com;host=localhost';
$user     = 'co-19-301.99sv-coco_c';
$password = 'Em4kxvSU';

// DBへ接続
try{
  $dbh = new PDO($dsn, $user, $password);

  // クエリの実行
  // $query = "SELECT * FROM TABLE_NAME";
  // $stmt = $dbh->query($query);

  // 表示処理
  // while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
  //     echo $row["name"];
  // }

}catch(PDOException $e){
  print("データベースの接続に失敗しました".$e->getMessage());
  die();
}

// 接続を閉じる
$dbh = null;
?>