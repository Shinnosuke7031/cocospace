<?php

/////////////////// 名前とコメントのデータを取得 ////////////////
if (isset($_GET["name"]) && isset($_GET["comment"])) {

  $fp = fopen("input_data.txt", "a+");
  
  $time = date("Y-m-d H:i:s");
  $name = $_GET["name"];
  $comment = $_GET["comment"];

  $last_line;//最後の行を取得
  while ($line = fgets($fp)) {
    $last_line = $line;
  }

  $last_elements = [];//最後の行の各要素
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

/////////////////// 指定された番号のコメントを削除 ////////////////
if (isset($_POST["delete_number"])) {

  $delete_number = $_POST["delete_number"];
  $fp = fopen("input_data.txt", "r");
  $deta = [];//削除番号以外の各行のデータ

  while ($line = fgets($fp)) {
    if (isset($line)) {//ファイルが空白ではない時
      $elements = explode("<>", $line);
      $index = $elements[0];
      if ($index != $delete_number) {
        array_push($deta, $line);
      }
    }
  }
  fclose($fp);

  $text_merge = "";

  foreach ($deta as $ele) {
    $text_merge .= $ele;
  }
  
  $fp = fopen("input_data.txt", "w");
  fwrite($fp, $text_merge);
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
    <form action="kadai2_4.php" method="get">
      <div class="form-element">
        <p>名前：</p>
        <input type="text" name="name" />
      </div>
      <div class="form-element set_btn">
        <p>コメント：</p>
        <input type="text" name="comment" />
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
    <form action="kadai2_4.php" method="post">
      <div class="form-element set_btn">
        <p>削除番号：</p><input type="number" name="delete_number" />
        <button class="btn-submit" type="submit">削除</button>
      </div>
    </form>
  
  </div>
  </body>
</html>