<?php

$isEditMode = 0;
/////////////////// 入力された名前とコメントのデータを取得 ////////////////
if (isset($_POST["name"]) && isset($_POST["comment"])) {

  $_normalAddMode = true;
  $edit_id = $_POST["JugeEditMode_inInputForm"];

  if ($edit_id) {
    $fp = fopen("input_data.txt", "r");
    $data = [];//削除番号以外の各行のデータ
    $time = date("Y-m-d H:i:s");
    $name = $_POST["name"];
    $comment = $_POST["comment"];

    while ($line = fgets($fp)) {
      if (isset($line)) {//ファイルが空白ではない時
        $elements = explode("<>", $line);
        $index = $elements[0];
        $edit_name = $elements[1];
        $edit_comment = $elements[2];
        if ($index == $edit_id) {
          $edited_line = $index . "<>" . $name . "<>" . $comment . "<>" . $time . PHP_EOL;
          array_push($data, $edited_line);
        } else {
          array_push($data, $line);
        }
      }
    }
    fclose($fp);

    $text_merge = "";

    foreach ($data as $ele) {
      $text_merge .= $ele;
    }

    $fp = fopen("input_data.txt", "w");
    fwrite($fp, $text_merge);
    fclose($fp);

    $_normalAddMode = false; 
  }

  if ($_normalAddMode) {
    $fp = fopen("input_data.txt", "a+");
    
    $time = date("Y-m-d H:i:s");
    $name = $_POST["name"];
    $comment = $_POST["comment"];
  
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

/////////////////// 指定された番号のコメントを表示(edit) ////////////////
if (isset($_POST["edit_number"])) {
  
  $edit_number = $_POST["edit_number"];
  $isEditMode = $edit_number;
  $fp = fopen("input_data.txt", "r");
  $deta = [];//削除番号以外の各行のデータ

  while ($line = fgets($fp)) {
    if (isset($line)) {//ファイルが空白ではない時
      $elements = explode("<>", $line);
      $index = $elements[0];
      $edit_name = $elements[1];
      $edit_comment = $elements[2];
      if ($index == $edit_number) {
        // echo $line;
        $name_form = $edit_name;
        $comment_form = $edit_comment;
      }
    }
  }
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
    <form action="kadai2_5.php" method="post">
      <input type="hidden" value=<?php echo $isEditMode; ?> name="JugeEditMode_inInputForm">
      <div class="form-element">
        <p>名前：</p>
        <input type="text" name="name" value=<?php
          if ($isEditMode) echo $name_form;
          else echo "";
        ?> >
      </div>
      <div class="form-element set_btn">
        <p>コメント：</p>
        <input type="text" name="comment" value=<?php
          if ($isEditMode) echo $comment_form;
          else echo "";
        ?> >
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
    <form class="form_mini" action="kadai2_5.php" method="post">
      <div class="form-element set_btn">
        <p>削除番号：</p><input type="number" name="delete_number">
        <button class="btn-submit" type="submit">削除</button>
      </div>
    </form>
    <form class="form_mini" action="kadai2_5.php" method="post">
      <div class="form-element set_btn">
        <input type="hidden" value=<?php echo $isEditMode; ?> name="JugeEditMode">
        <p>編集番号：</p><input type="number" name="edit_number">
        <button class="btn-submit" type="submit">番号を指定</button>
      </div>
    </form>
  
  </div>
  </body>
</html>