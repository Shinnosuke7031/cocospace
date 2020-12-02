<?php
  if (isset($_GET["trans_data"])) {
    $data = $_GET["trans_data"];
    $fp = fopen("kadai1_5_output.txt", "w");
    fwrite($fp, $data);
    fclose($fp);
    echo "入力されたデータ<span class=\"emphasis\">[".$data."]</span>は\"kadai1_5_output.txt\"に保存されました";
  } else {
    echo "文字を入力してください";
  }
?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UFT-8">
    <title>課題1_5</title>
    <style>
      .emphasis {
        color: red;
      }
    </style>
  </head>
<body>
  <h1>文字列データの送信</h1>
  <form action="kadai1_5.php" method="get">
    <input type="text" name="trans_data"><br/>
    <input type="submit" value="送信">
  </form>
  </body>
</html>