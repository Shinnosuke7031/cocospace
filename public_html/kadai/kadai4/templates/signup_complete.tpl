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
    <h1><a href="{$urls->top_url}">簡易掲示板</a></h1>
  </div>

  <div class="container">
    {if $obj->isSignupOK}
    <h1 class="page_title">本登録完了</h1>
    <div class="signup_info">
      <p>ようこそ「</p>
      <p class="emphasis"> {$obj->name} </p>
      <p>」さん</p>
    </div>
    <div class="signup_info">
      <p>ログインID：</p>
      <p class="emphasis"> {$obj->user_id} </p>
    </div>
    <div class="signup_info">
      <p>パスワード：</p>
      <p class="emphasis"> {$obj->password} </p>
    </div>
    
    <p>ログインは<a href="{$urls->top_url}" >こちら</a></p>

    {/if}
  
  </div>

  </body>
</html>