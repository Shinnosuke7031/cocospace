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
    <script type="text/javascript">
      {literal}
        function check(){
        	if(confirm('削除してよろしいですか？')){
        		return true;
        	}
        	else{
        		alert('キャンセルされました');
        		return false;
        	}
        }
      {/literal}
    </script>
  </head>
<body>
  <div class="header">
    <p> ようこそ： {$obj->user_name} さん </p>
    <h1><a href={$urls->top_url}>簡易掲示板</a></h1>
    <form class="logout_form" action={$urls->logout_url} method="POST">
      <button class="logout_btn btn">ログアウト</button>
    </form>
  </div>

  <div class="container">

    <h1 class="page_title">掲示板</h1>

    {* <form class="form_sort_btn" action="board.php" method="post">
      <input class="btn_input_anime btn_input" type="submit" name="up_sort" value="昇順ソート">
      <input class="btn_input_anime btn_input" type="submit" name="down_sort" value="降順ソート">
    </form> *}

    <form class="form_file_upload" action="board.php" enctype="multipart/form-data" method="post">
      <div class="file_upload">
        <div class="form-element2">
          <label>画像/動画アップロード</label>
          <input class="btn_input2" type="file" name="upfile">
        </div>
        ※対応形式 : 画像(jpeg, png, gif), 動画(mp4)<br>
        <input class="btn_input_anime btn_input" type="submit" value="アップロード">
      </div>
    </form>
    <br/>
    <form action="board.php" method="post">
      <input type="hidden" value={$obj->isEditMode} name="JugeEditMode_inInputForm">
      <input type="hidden" value={$obj->edit_id} name="edit_id" >
      {if $obj->user_agent=="smartphone"}
        <div class="form-element set_btn">
      {elseif $obj->user_agent=="mobile"}
        <div class="form-element set_btn">
      {else}
        <div class="form-element set_btn submit_form">
      {/if}
        <p>コメント：</p>
        <input type="text" name="comment" value="{if $obj->isEditMode}{$obj->comment_form}{else}{/if}">
        <button class="btn-submit btn" type="submit">投稿</button>
      </div>
      <div class="comment_lineup">
        <div class="info ex">
          <p class="main_info">ID : id， <span style="font-weight: bold;"> 名前 </span><span style="font-weight: bold;">「コメント」</span> </p><p class="time_info">時間</p>
        </div>
        {foreach $comments as $key => $comment}

          {if $types[$key] == "text"}
            <div class="info">
              <p class="main_info">ID : {$key+1} ，<span style="font-weight: bold;"> {$names[$key]} </span><span style="font-weight: bold;">「{$comment}」</span> </p><p class="time_info"> {$times[$key]} </p>
            </div>
          {elseif $types[$key] == "jpeg" || $types[$key] == "png" || $types[$key] == "gif"}
            <div class="info main_image">
              <p class="main_info">ID : {$key+1} ，<span style="font-weight: bold;"> {$names[$key]} </span>
                <img class="images" src='{$urls->import_url}?target={$fnames[$key]}'>
              </p>
              <p class="time_info"> {$times[$key]} </p>
              </p>
            </div>
          {elseif $types[$key] == "mp4"}
            <div class="info main_image">
              <p class="main_info">ID : {$key+1} ，<span style="font-weight: bold;"> {$names[$key]} </span>
                <video src='{$urls->import_url}?target={$fnames[$key]}' width="150" height="70" preload="none" controls playsinline type="video/mp4"></video>
                {* <video width="150px" height="70px" autoplay muted playsinline controls>
	                <source src='{$urls->import_url}?target={$fnames[$key]}' type="video/mp4">
                </video> *}
              </p>
              <p class="time_info"> {$times[$key]} </p>
              </p>
            </div>
          {/if}

        {/foreach}
      </div>
    </form>

  <form class="form_mini" action="board.php" method="post" onsubmit="return check()">
    {* {if $obj->sort == "up"}
      <input type="hidden" name="up_sort2" value="昇順ソート">     
    {elseif $obj->sort == "down"}
      <input type="hidden" name="down_sort2" value="降順ソート">
    {/if} *}
    <div class="form-element">
      <p class="del_edit_form">削除番号：</p><input type="number" name="delete_number">
    </div>
    <div class="form-element set_btn">
      <p class="del_edit_form">パスワード：</p>
      <input type="password" name="password_delete">
      <button class="btn-submit btn" type="submit">削除</button>
    </div>
  </form>
  <form class="form_mini" action="board.php" method="post">
    {* {if $obj->sort == "up"}
      <input type="hidden" name="up_sort2" value="昇順ソート">     
    {elseif $obj->sort == "down"}
      <input type="hidden" name="down_sort2" value="降順ソート">
    {/if} *}
    <div class="form-element">
      <input type="hidden" value={$obj->isEditMode} name="JugeEditMode">
      <p class="del_edit_form">編集番号：</p><input type="number" name="edit_number">
    </div>
    <div class="form-element set_btn">
      <p class="del_edit_form">パスワード：</p>
      <input type="password" name="password_edit">
      <button class="btn-submit btn" type="submit">番号を指定</button>
    </div>
  </form>

    <!-- <form action={ $logout_url } method="POST" >
      <button>ログアウト</button>
    </form> -->
  
  </div>
  </body>
</html>