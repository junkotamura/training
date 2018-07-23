<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>ユーザ登録ページ</title>
    <style>
      .register {
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
      .blue {
        color : blue ;
      }
      .red {
        color : red ;
      }
    </style>
  </head>
  <body>
    <div class="content">
      <div class="register">
        <form method="post" action="">
          <div>ユーザー名：<input type="text" name="user_name" value="" /></div>
          <div>パスワード：<input type="text" name="password" value="" /></div>
          <div><input type="submit" name="send" value="ユーザーを新規作成する" /></div>
        </form>
        <?php if (count($err_msg)) { ?>
          <?php foreach ((array)$err_msg as $value) { ?>
            <p class="red"><?php print $value; ?></p>
          <?php } ?>
        <?php } ?>
        <p class="blue"><?php print $msg; ?></p>
        <?php if (empty($msg) === FALSE) { ?>
          <a href="http://codecamp22362.lesson8.codecamp.jp//php/shop/model/login.php">
            <p>ログインページに移動する</p>
          </a>
        <?php } ?>
       </div>
    </div>
 </body>
