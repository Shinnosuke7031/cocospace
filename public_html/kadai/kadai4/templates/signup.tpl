<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UFT-8">
    <title>簡易掲示板</title>
    <link rel="stylesheet" type="text/css" href="index.css">
    {if $obj->user_agent=="smartphone"}
    <link rel="stylesheet" type="text/css" href="comn/smartphone.css">
    <meta name="viewport" content="width=300">
    {/if}
    {if $obj->user_agent=="mobile"}
      <link rel="stylesheet" type="text/css" href="comn/smartphone.css">
      <meta name="viewport" content="width=300">
    {/if}
  </head>
<body>

  <div class="header2">
    <h1><a href={$urls->top_url}>簡易掲示板</a></h1>
  </div>

  <div class="container">
    
    <h1 class="page_title">登録フォーム</h1>

    {if !$obj->isFormComplete}
    <!-- 入力フォーム -->
    <form class="form_signup" action={$urls->url_signup} method="post">
      <div>
        <p>名前</p>
        {if $obj->user_agent=="PC"}
          <p class="form_nav_kugiri">：</p>
        {/if}
        <input type="text" name="input_name" value={$obj->input_name}>
        <p class="input_alert">{$obj->alert_name}</p>
      </div>
      <div>
        <p>メールアドレス</p>
        {if $obj->user_agent=="PC"}
          <p class="form_nav_kugiri">：</p>
        {/if}
        <input type="email" name="input_to" value={$obj->input_to}>
        <p class="input_alert">{$obj->alert_to}</p>
      </div>
      <div>
        <p>パスワード</p>
        {if $obj->user_agent=="PC"}
          <p class="form_nav_kugiri">：</p>
        {/if}
        <input type="password" name="input_password" value={$obj->input_password}>
        <p class="input_alert"> {$obj->alert_pass1} </p>
      </div>
      <div>
        <p>パスワード(確認)</p>
        {if $obj->user_agent=="PC"}
          <p class="form_nav_kugiri">：</p>
        {/if}
        <input type="password" name="check_password">
        <p class="input_alert"> {$obj->alert_pass2} </p>
        <button class="signup_btn btn" type="submit">登録</button>
      </div>
    </form>

    {else}
    <!-- 登録完了 -->
    <p>入力されたメールアドレスにメールを送信しました。</p>
    <p>メールの案内にそって本登録を完了してください。</p>
    {/if}
  </div>

  </body>
</html>