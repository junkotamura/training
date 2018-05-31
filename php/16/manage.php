<?php
date_default_timezone_set('Asia/Tokyo');
$date = date('Y-m-d H:i:s');
$name = 0;
$price = 0;

$db_host = 'localhost';
$db_name = 'codecamp22362';
$db_user = 'codecamp22362';
$db_pass = 'WIOCLRNA';
$charset  = 'utf8';

$img_dir    = './img/';    // アップロードした画像ファイルの保存ディレクトリ

// データベースへ接続する
$link = mysqli_connect( $db_host, $db_user, $db_pass, $db_name );
if ( $link !== false ) {
mysqli_set_charset($link, 'utf8');
    $msg     = '';
    $err_msg = '';

    if ( isset( $_POST['send'] ) === true ) {

        $name     = $_POST['name'];
        $comment = $_POST['price'];

        if ( $name !== '' && $price !== '' ) {

            $query = " INSERT INTO test_drink_master ( "
                   . "    drink_name , "
                   . "    price , "
                   . "    img , "
                   . "    create_datetime "
                   . " ) VALUES ( "
                   . "'" . mysqli_real_escape_string( $link, $name ) ."', "
                   . "'" . mysqli_real_escape_string( $link, $price ) . "',"
                   . "'" . mysqli_real_escape_string( $link, $img ) . "',"
                   . "'" . mysqli_real_escape_string( $link, $date) . "'"
                   ." ) ";

            $res   = mysqli_query( $link, $query );
        }
    }

    $query  = "SELECT drink_id, drink_name, price, img, create_datetime FROM test_drink_master";
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
        <title>自動販売機</title>
    </head>
    <body>
      <h1>自動販売機管理ツール</h1>
      <h2>新規商品追加</h2>
  <?php if (mb_strlen($name) === 0) { ?>
  <?php print '商品名を入力してください'; ?>
  <?php } ?>
  <?php if (mb_strlen($price) === 0) { ?>
  <?php print '値段を入力してください'; ?>
  <?php } ?>
        <form method="post" action="">
            名前：<input type="text" name="name" value="" /><br>
            値段：<input type="text" name="price" value="" /><br>
           <div><input type="submit" name="send" value="ファイルを選択" /></div><br>
           <div><input type="submit" name="send" value="商品を追加" /></div><br>
        </form>
      <h2>商品情報変更</h2>
      <p>商品一覧</p>
  <?php foreach( $data as $key => $val ) { ?>
        <?php echo $val['img'] . ' ' . $val['drink_name'] . ' ' . $val['price'] . '<br>';  ?>
  <?php } ?>
    </body>
</html>
