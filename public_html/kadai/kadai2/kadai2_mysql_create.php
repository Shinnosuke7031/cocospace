<?php
$dsn      = 'mysql:dbname=co_19_301_99sv_coco_com;host=localhost';
$user     = 'co-19-301.99sv-coco_c';
$password = 'Em4kxvSU';

// DBへ接続
try{
  $dbh = new PDO($dsn, $user, $password);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  

  // クエリの実行
  $query = "CREATE TABLE kadai2_MySQL_TEST (
		id INT AUTO_INCREMENT,
		name TEXT,
		comment TEXT,
		time TEXT,
    password TEXT,
    PRIMARY KEY(id)
  ) charset=utf8";
  $res = $dbh->query($query);

  // クエリの実行
  $query = "SELECT * FROM kadai2_mysql_test";
  $stmt = $dbh->query($query);

  // 表示処理
  while($row = $stmt->fetch()){
      echo $row["id"];

  }

}catch(PDOException $e){
  print("データベースの接続に失敗しました".$e->getMessage());
  die();
}

// 接続を閉じる
$dbh = null;
?>