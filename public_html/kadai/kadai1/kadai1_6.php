<?php
  if (isset($_GET["trans_data"])) {
    $data = $_GET["trans_data"];
    $fp = fopen("kadai1_6_output.txt", "a");
    fwrite($fp, $data . PHP_EOL);
    fclose($fp);
    echo "入力されたデータ<span class=\"emphasis\">[".$data."]</span>は\"kadai1_6_output.txt\"に追記されました";
  } else {
    echo "文字を入力してください";
  }
?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UFT-8">
    <title>課題1_6</title>
    <style>
      .emphasis {
        color: red;
      }
    </style>
  </head>
<body>
  <h1>文字列データの送信</h1>
  <form action="kadai1_6.php" method="get">
    <input type="text" name="trans_data"><br/>
    <input type="submit" value="送信">
  </form>
  </body>
</html>