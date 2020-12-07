<?php
$dsn      = 'mysql:dbname=co_19_301_99sv_coco_com;host=localhost';
$user     = 'co-19-301.99sv-coco_c';
$password = 'Em4kxvSU';

// DBへ接続
try{
  $dbh = new PDO($dsn, $user, $password);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  echo "接続成功<br/>";
  $delete_id = 5;//  編集したいID
  echo "ID : " . $delete_id . "を削除します。";
  echo "================== Before =================<br/>";

  // クエリの実行(SELECT) Before
  $query = "SELECT * FROM kadai2_MySQL_TEST";
  $stmt = $dbh->query($query);

  $row_count = $stmt->rowCount();
  echo "データ総数：" . $row_count . "個<br/>";

  // 表示処理
  while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    $id = $row["id"];
    $name = $row["name"];
    $comment = $row["comment"];
    $time = $row["time"];
    $password = $row["password"];
      echo  "id : " . $id . ", name : " . $name . "<br/>";
      echo  "comment : " . $comment . ", time : " . $time . ", password : " . $password . "<br/>";
  }

  echo "================== After =================<br/>";
  

  // クエリの作成(DELETE)
  $query = 'DELETE FROM kadai2_MySQL_TEST WHERE id = :id';
  $stmt = $dbh->prepare($query);

  // 実行
  $stmt->execute(array(':id' => $delete_id));


  // クエリの実行(SELECT) After
  $query = "SELECT * FROM kadai2_MySQL_TEST";
  $stmt = $dbh->query($query);

  $row_count = $stmt->rowCount();
  echo "データ総数：" . $row_count . "個<br/>";

  // 表示処理
  while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    $id = $row["id"];
    $name = $row["name"];
    $comment = $row["comment"];
    $time = $row["time"];
    $password = $row["password"];
      echo  "id : " . $id . ", name : " . $name . "<br/>";
      echo  "comment : " . $comment . ", time : " . $time . ", password : " . $password . "<br/>";
  }

}catch(PDOException $e){
  print("データベースの接続に失敗しました".$e->getMessage());
  die();
}

// 接続を閉じる
$dbh = null;
?>