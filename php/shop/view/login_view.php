<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>ログインページ</title>
    <style>
      .login {
        margin-top: 50px;
        margin-left : auto ;
        margin-right : auto ;
        width: 400px;
      }
      .content {
        margin-left : auto ;
        margin-right : auto ;
        width: 960px;
      }
      .red {
        color : red ;
      }
    </style>
  </head>
  <body>
    <div class="content">
      <div class="login">
        <form method="post" action="">
          <div><input type="text" name="user_name" value="" placeholder="ユーザー名"/></div>
          <div><input type="text" name="password" value="" placeholder="パスワード" /></div>
          <div><input type="submit" name="login" value="ログイン" /></div>
        </form>
        
        <?php if (count($err_msg)) { ?>
          <?php foreach ((array)$err_msg as $value) { ?>
            <p class="red"><?php print $value; ?></p>
          <?php } ?>
        <?php } ?>
        <a href="http://codecamp22362.lesson8.codecamp.jp//php/shop/controller/register.php">
          <p>ユーザーの新規作成</p>
        </a>
       </div>
    </div>
 </body>
