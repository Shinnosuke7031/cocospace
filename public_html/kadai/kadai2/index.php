<?php

if (isset($_GET["name"]) && isset($_GET["comment"])) {

  $fp = fopen("input_data.txt", "a+");
  
  $time = date("Y-m-d H:i:s");
  $name = $_GET["name"];
  $comment = $_GET["comment"];

  $last_line;//最後の行を取得
  while ($line = fgets($fp)) {
    $last_line = $line;
  }

  $last_elements = explode("<>", $last_line);
  $last_index = $last_elements[0];
  $index = $last_index + 1;//最後の行のindex+1
  
  $text_mergred = $index . "<>" . $name . "<>" . $comment . "<>" . $time;
  fwrite($fp, $text_mergred . PHP_EOL);
  fclose($fp);
}

?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UFT-8">
    <title>簡易掲示板</title>
    <link rel="stylesheet" type="text/css" href="index.css">
  </head>
<body>
  <div class="container">

    <h1>簡易掲示板</h1>
    <form action="index.php" method="get">
      <div class="form-element">
        <p>名前：</p>
        <input type="text" name="name">
      </div>
      <div class="form-element">
        <p>コメント：</p>
        <input type="text" name="comment">
      </div>
      <button class="btn-submit" type="submit">送信</button>
    </form>
  
  </div>
  </body>
</html>