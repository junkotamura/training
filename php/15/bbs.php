<?php
date_default_timezone_set('Asia/Tokyo');
$date = date('Y-m-d H:i:s');
$name = 0;
$comment = 0;
$name_max = 20;
$comment_max = 100;

$db_host = 'localhost';
$db_name = 'codecamp22362';
$db_user = 'codecamp22362';
$db_pass = 'WIOCLRNA';
$charset  = 'utf8';

// データベースへ接続する
$link = mysqli_connect( $db_host, $db_user, $db_pass, $db_name );
if ( $link !== false ) {
mysqli_set_charset($link, 'utf8');
    $msg     = '';
    $err_msg = '';

    if ( isset( $_POST['send'] ) === true ) {

        $name     = $_POST['name'];
        $comment = $_POST['comment'];

        if ( $name !== '' && $comment !== '' ) {

            $query = " INSERT INTO post ( "
                   . "    user_name , "
                   . "    user_comment , "
                   . "    create_datetime "
                   . " ) VALUES ( "
                   . "'" . mysqli_real_escape_string( $link, $name ) ."', "
                   . "'" . mysqli_real_escape_string( $link, $comment ) . "',"
                   . "'" . mysqli_real_escape_string( $link, $date) . "'"
                   ." ) ";

            $res   = mysqli_query( $link, $query );
        }
    }

    $query  = "SELECT id, user_name, user_comment, create_datetime FROM post";
    $res    = mysqli_query( $link,$query );
    $data = array();
    while( $row = mysqli_fetch_assoc( $res ) ) {
        array_push( $data, $row);
    }
    asort( $data );
    
} else {
    echo "データベースの接続に失敗しました";
}

// データベースへの接続を閉じる
mysqli_close( $link );
?>

<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title>ひとこと掲示板</title>
    </head>
    <body>
      <h1>ひとこと掲示板</h1>
       <?php if (mb_strlen($name) > $name_max) { ?>
    <ul>
      <li>
  <?php print '名前は20文字以内で入力してください'; ?>
      </li>
    </ul>
  <?php } ?>
  <?php if (mb_strlen($name) === 0) { ?>
    <ul>
      <li>
  <?php print '名前を入力してください'; ?>
      </li>
    </ul>
  <?php } ?>
  <?php if (mb_strlen($comment) > $comment_max) { ?>
    <ul>
      <li>
  <?php print 'ひとことは100文字以内で入力してください'; ?>
      </li>
    </ul>
  <?php } ?>
  <?php if (mb_strlen($comment) === 0) { ?>
    <ul>
      <li>
  <?php print 'ひとことを入力してください'; ?>
      </li>
    </ul>
  <?php } ?>
        <form method="post" action="">
            名前：<input type="text" name="name" value="" />
            ひとこと：<input type="text" name="comment" size="50" value="" />
           <input type="submit" name="send" value="送信" />
        </form>
        <!-- ここに、書き込まれたデータを表示する -->
  <?php foreach( $data as $key => $val ) { ?>
    <ul>
      <li>
        <?php echo $val['user_name'] . ' ' . $val['user_comment'] .  ' ' . $val['create_datetime'] . '<br>';  ?>
      </li>
    </ul>
  <?php } ?>
    </body>
</html>
