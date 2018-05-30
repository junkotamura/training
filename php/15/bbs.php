<?php
    date_default_timezone_set('Asia/Tokyo');
    $host     = 'localhost';
    $username = 'codecamp22362';   // MySQLのユーザ名
    $password = 'WIOCLRNA';       // MySQLのパスワード
    $dbname   = 'codecamp22362';   // MySQLのDB名(今回、MySQLのユーザ名を入力してください)
    $charset  = 'utf8';   // データベースの文字コード 

    $filename = './review.txt';
    $name = 0;
    $comment = 0;
    $name_max = 20;
    $comment_max = 100;
    $log = date('Y-m-d H:i:s');

    // MySQL用のDSN文字列
    $dsn = 'mysql:dbname='.$dbname.';host='.$host.';charset='.$charset;
 
    try {
      // データベースに接続
      $dbh = new PDO($dsn, $username, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4'));
      $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
 
      // SQL文(データを格納)を作成
      $sql = 'INSERT INTO post(user_name,user_comment,create_datetime)
        VALUES('$name','$comment','$log');
      // SQL文を実行する準備
      $stmt = $dbh->prepare($sql);
      // SQLを実行
      $stmt->execute();
      // レコードの取得
      $rows = $stmt->fetchAll();
 
      var_dump($rows);
 
    } catch (PDOException $e) {
      echo '接続できませんでした。理由：'.$e->getMessage();
    }
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>ひとこと掲示版</title>
  </head>
  <body>
  </body>
</html>
