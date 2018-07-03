<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
  </head>
    <body>
      <h1>ひとこと掲示板</h1>
        <?php if (count($err_msg)) { ?>
          <?php foreach($err_msg as $value) { ?>
            <ul>
              <li><?php print $value; ?></li>
            </ul>
          <?php } ?>
        <?php } ?>
      <form method="post" action="">
        名前：<input type="text" name="name" value="" />
        ひとこと：<input type="text" name="comment" size="50" value="" />
        <input type="submit" name="send" value="送信" />
      </form>
      <!-- ここに、書き込まれたデータを表示する -->
        <?php foreach($comment_data as $key => $value) { ?>
          <ul>
            <li>
            <?php echo $value['user_name'] . ' ' . $value['user_comment'] .  ' ' . $value['create_datetime'] . '<br>';  ?>
            </li>
          </ul>
        <?php } ?>
    </body>
</html>
