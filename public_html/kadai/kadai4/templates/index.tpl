<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UFT-8">
  <title>簡易掲示板</title>
  <link rel="stylesheet" type="text/css" href="comn/index.css">
</head>

<body>

  <div class="header">
    <h1><a href="{$urls->top_url}">簡易掲示板</a></h1>
  </div>

  <div class="container">

    <form class="form_signup" action="{$urls->top_url}" method="post">
      <div>
        <p>ID</p>
        <p class="form_nav_kugiri">：</p>
        <input type="text" name="input_id" value="{$obj->input_id}" >
      </div>
      <div>
        <p>パスワード</p>
        <p class="form_nav_kugiri">：</p>
        <input type="password" name="input_password">
        <button class="login_btn btn" type="submit">ログイン</button>
        <p class="input_alert">{$obj->alert_pass1}</p>
      </div>
    </form>

    <p>初めての方は<a href="{$urls->url_signup}"> 登録フォームへ </a></p>

  </div>
</body>

</html>