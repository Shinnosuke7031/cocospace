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