<?php
$dsn      = 'mysql:dbname=co_19_301_99sv_coco_com;host=localhost';
$user     = 'co-19-301.99sv-coco_c';
$password = 'Em4kxvSU';

// DBへ接続
try{
  $dbh = new PDO($dsn, $user, $password);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  echo "接続成功<br/>";
  
  $name = 'test-man';
  $comment = 'This is Test.';
  $time = (string)(date("Y-m-d H:i:s"));
  $password  = 'abcd';

  // クエリの作成(INSERT)
  $query = 'INSERT INTO kadai2_MySQL_TEST(name, comment, time, password) VALUES(:name, :comment, :time, :password)';
  $stmt = $dbh->prepare($query);

  $stmt -> bindValue(':name', $name, PDO::PARAM_STR_CHAR);
  $stmt -> bindValue(':comment', $comment, PDO::PARAM_STR_CHAR);
  $stmt -> bindValue(':time', $time, PDO::PARAM_STR_CHAR);
  $stmt -> bindValue(':password', $password, PDO::PARAM_STR_CHAR);

  // 実行
  $stmt->execute();

  // クエリの実行(SELECT)
  $query = "SELECT * FROM kadai2_MySQL_TEST";
  $stmt = $dbh->query($query);

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