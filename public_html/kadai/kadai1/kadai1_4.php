<?php
  if (isset($_GET["trans_data"])) {
    $data = $_GET["trans_data"];
    echo $data;
  }
?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UFT-8">
    <title>課題1_4</title>
  </head>
<body>
  <h1>文字列データの送信</h1>
  <form action="kadai1_4.php" method="get">
    <input type="text" name="trans_data"><br/>
    <input type="submit" value="送信">
  </form>
  </body>
</html>