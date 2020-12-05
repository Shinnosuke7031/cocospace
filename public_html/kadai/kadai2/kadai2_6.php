<?php

$isEditMode = 0;
/////////////////// 入力された名前とコメントのデータを取得 ////////////////
if (isset($_POST["name"]) && isset($_POST["comment"])) {
  if($_POST["name"] != "" && $_POST["comment"] != "") {

    $_normalAddMode = true;
    $edit_id = $_POST["JugeEditMode_inInputForm"];
    
    if ($edit_id) {
      $fp = fopen("input_data_with_password.txt", "r");
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
          $edit_pass = $elements[4];
          if ($index == $edit_id) {
            $edited_line = $index . "<>" . $name . "<>" . $comment . "<>" . $time . "<>" . $edit_pass . PHP_EOL;
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
      
      $fp = fopen("input_data_with_password.txt", "w");
      fwrite($fp, $text_merge);
      fclose($fp);  
      
      $_normalAddMode = false;
      $isEditMode = 0;
    }
    
    if ($_normalAddMode) {
      if($_POST["password"] != "") {

        $fp = fopen("input_data_with_password.txt", "a+");
        
        $time = date("Y-m-d H:i:s");
        $name = $_POST["name"];
        $comment = $_POST["comment"];
        $password = $_POST["password"];
        
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
        
        $text_mergred = $index . "<>" . $name . "<>" . $comment . "<>" . $time . "<>" . $password;
        fwrite($fp, $text_mergred . PHP_EOL);
        fclose($fp);
      }
    }
    
  }

}

/////////////////// 指定された番号のコメントを削除 ////////////////
if (isset($_POST["delete_number"]) && isset($_POST["password_delete"])) {

  $delete_number = $_POST["delete_number"];
  $fp = fopen("input_data_with_password.txt", "r");
  $deta = [];//削除番号以外の各行のデータ

  $password_delete = $_POST["password_delete"];
  $password_delete_check = "";

  while ($line = fgets($fp)) {
    if (isset($line)) {//ファイルが空白ではない時
      $elements = explode("<>", $line);
      $index = $elements[0];
      $delete_pass = $elements[4];
      if ($index == $delete_number) {
        $password_delete_check = str_replace(PHP_EOL, '', $delete_pass);
      } else {
        array_push($deta, $line);
      }
    }
  }
  fclose($fp);

  $text_merge = "";

  foreach ($deta as $ele) {
    $text_merge .= $ele;
  }

  if ($password_delete == $password_delete_check) {
    $fp = fopen("input_data_with_password.txt", "w");
    fwrite($fp, $text_merge);
    fclose($fp);
  }
  else echo "<script type='text/javascript'>alert('パスワードが違います。');</script>"; 
}

/////////////////// 指定された番号のコメントを表示(edit) ////////////////
if (isset($_POST["edit_number"]) && isset($_POST["password_edit"])) {
  
  $password_edit = $_POST["password_edit"];
  $password_edit_check = "";

  $edit_number = $_POST["edit_number"];
  $fp = fopen("input_data_with_password.txt", "r");
  $deta = [];//削除番号以外の各行のデータ
  
  while ($line = fgets($fp)) {
    if (isset($line)) {//ファイルが空白ではない時
      $elements = explode("<>", $line);
      $index = $elements[0];
      $edit_name = $elements[1];
      $edit_comment = $elements[2];
      $edit_pass = $elements[4];
      if ($index == $edit_number) {
        $name_form = $edit_name;
        $comment_form = $edit_comment;
        $password_edit_check = str_replace(PHP_EOL, '', $edit_pass);
        $password_form = $password_edit_check;
      }
    }
  }
  fclose($fp);
  if ($password_edit == $password_edit_check) $isEditMode = $edit_number;
  else echo "<script type='text/javascript'>alert('パスワードが違います。');</script>";

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
    <form action="kadai2_6.php" method="post">
      <input type="hidden" value=<?php echo $isEditMode; ?> name="JugeEditMode_inInputForm">
      <div class="form-element">
        <p>名前：</p>
        <input type="text" name="name" value=<?php
          if ($isEditMode) echo $name_form;
          else echo "";
        ?> >
      </div>
      <div class="form-element">
        <p>コメント：</p>
        <input type="text" name="comment" value=<?php
          if ($isEditMode) echo $comment_form;
          else echo "";
        ?> >
      </div>
      <div class="form-element set_btn">
        <p>パスワード：</p>
        <input type="password" name="password" value=<?php
          if ($isEditMode) echo $password_form;
          else echo ""; ?> >
        <button class="btn-submit" type="submit">送信</button>
      </div>
      <div class="comment_lineup">
        <?php 
          $fp = fopen("input_data_with_password.txt", "r");
          while ($line = fgets($fp)) {
            $line_elements = explode("<>", $line);
            $length = count($line_elements);
            $i = 0;
            echo "<p>";
            foreach ($line_elements as $ele) {
              if ($i != $length - 1)  echo $ele . " ";
              $i++;
            }
            echo "</p>";
          }
          fclose($fp);
          ?>
      </div>
    </form>
    <form class="form_mini" action="kadai2_6.php" method="post">
      <div class="form-element">
        <p>削除番号：</p><input type="number" name="delete_number">
      </div>
      <div class="form-element set_btn">
        <p>パスワード：</p>
        <input type="password" name="password_delete">
        <button class="btn-submit" type="submit">削除</button>
      </div>
    </form>
    <form class="form_mini" action="kadai2_6.php" method="post">
      <div class="form-element">
        <input type="hidden" value=<?php echo $isEditMode; ?> name="JugeEditMode">
        <p>編集番号：</p><input type="number" name="edit_number">
      </div>
      <div class="form-element set_btn">
        <p>パスワード：</p>
        <input type="password" name="password_edit">
        <button class="btn-submit" type="submit">番号を指定</button>
      </div>
    </form>
  
  </div>
  </body>
</html>