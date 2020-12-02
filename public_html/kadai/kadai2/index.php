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

  $last_elements = [];
  $index = 1;

  if (isset($last_line)) {//ファイルが空白ではない時，最後の行のindexを参照する
    $last_elements = explode("<>", $last_line);
    $last_index = $last_elements[0];
    $index = $last_index + 1;//最後の行のindex+1
  }
  
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
      <div class="form-element set_btn">
        <p>コメント：</p>
        <input type="text" name="comment">
        <button class="btn-submit" type="submit">送信</button>
      </div>
      <div class="comment_lineup">
        <?php 
          $fp = fopen("input_data.txt", "r");
          while ($line = fgets($fp)) {
            $line_elements = explode("<>", $line);
            echo "<p>";
            foreach ($line_elements as $ele) {
              echo $ele . " ";
            }
            echo "</p>";
          }
          fclose($fp);
        ?>
      </div>
    </form>
  
  </div>
  </body>
</html>