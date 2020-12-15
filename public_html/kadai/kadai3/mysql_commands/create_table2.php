<?php
$dsn      = 'mysql:dbname=co_19_301_99sv_coco_com;host=localhost';
$user     = 'co-19-301.99sv-coco_c';
$password = 'Em4kxvSU';

require("../funk.php");

// DBへ接続
try{
  $dbh = new PDO($dsn, $user, $password);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // クエリの実行
  // $query = "CREATE TABLE user (
	// 	id INT AUTO_INCREMENT,
  //   user_id TEXT,
	// 	name TEXT,
  //   password TEXT,
  //   isTemporary BOOLEAN,
  //   time_temporary DATETIME,
  //   PRIMARY KEY(id)
  // ) charset=utf8";
  // $res = $dbh->query($query);

  // クエリの作成(INSERT)
  // $query = 'INSERT INTO user(user_id, name, password, isTemporary, time_temporary) VALUES(:user_id, :name, :password, :isTemporary, :time_temporary)';
  // $query = 'DELETE FROM user WHERE id = 2';
  // $stmt = $dbh->prepare($query);
  // $user_id = "bbb";
  // $name = "test woman";
  // $password = "abcd";
  // $isTemporary = false;
  // $time_temporary = now();
  // $stmt -> bindValue(':user_id', $user_id, PDO::PARAM_STR_CHAR);
  // $stmt -> bindValue(':name', $name, PDO::PARAM_STR_CHAR);
  // $stmt -> bindValue(':password', $password, PDO::PARAM_STR_CHAR);
  // $stmt -> bindValue(':isTemporary', $isTemporary, PDO::PARAM_INT);
  // $stmt -> bindValue(':time_temporary', $time_temporary, PDO::PARAM_STR_CHAR);

  // 実行
  // $stmt->execute();

  // クエリの実行(SELECT)
  $query = "SELECT * FROM user";
  $stmt = $dbh->query($query);

  // 表示処理
  while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    $id = $row["id"];
    $user_id = $row["user_id"];
    $name = $row["name"];
    $password = $row["password"];
    $isTemporary = $row["isTemporary"];
    $time_temporary = $row["time_temporary"];
      echo  "id : " . $id . ", user_id : " . $user_id . ", name : " . $name . "<br/>";
      echo  "password : " . $password . ", isTemporary :" . $isTemporary . ", time_temporary : " . $time_temporary . "<br/>";
  }

}catch(PDOException $e){
  print("データベースの接続に失敗しました".$e->getMessage());
  die();
}

// 接続を閉じる
$dbh = null;
?>