<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UFT-8">
  <title>簡易掲示板</title>
  <link rel="stylesheet" type="text/css" href="comn/index.css">
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

  {if $obj->user_agent=="smartphone" || $obj->user_agent=="mobile"}
    <div class="header2">
      <h1><a href="{$urls->top_url}">簡易掲示板</a></h1>
    </div>

    <div class="container">
      <form class="form_signup" action="{$urls->top_url}" method="post">
        <div>
          <p>ID</p>
          <input type="text" name="input_id" value="{$obj->input_id}" >
        </div>
        <div>
          <p>パスワード</p>
          <input type="password" name="input_password">
          <button class="login_btn btn" type="submit">ログイン</button>
          <p class="input_alert">{$obj->alert_pass1}</p>
        </div>
      </form>
      <p class="to_signup">初めての方は<a href="{$urls->url_signup}"> 登録フォームへ </a></p>
    </div>
  {else}
    <div class="header2">
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
  {/if}

    {* {assign var="ua" value=$smarty.server.HTTP_USER_AGENT|lower}
    /*PCブラウザかそれ以外の判定*/
    {if $ua|regex_replace:'/.*ipad.*/':'ipad' == 'ipad'}
      //タブレット
    {elseif $ua|regex_replace:'/.*android.*/':'android' == 'android' || $ua|regex_replace:'/.*iphone.*/':'iphone' == 'iphone' || $ua|regex_replace:'/.*ipod.*/':'ipod' == 'ipod' || $ua|regex_replace:'/.*windows phone.*/':'windows phone' == 'windows phone'}
      //スマホ
    {else}
      //PC
    {/if} *}

    {* {$smarty.server.HTTP_USER_AGENT} *}

  
</body>

</html>