<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>DB操作</title>
  </head>
  <body>
    <?php
    $host     = 'localhost';
    $username = 'codecamp22362';   // MySQLのユーザ名
    $password = 'WIOCLRNA';       // MySQLのパスワード
    $dbname   = 'codecamp22362';   // MySQLのDB名(今回、MySQLのユーザ名を入力してください)
    $charset  = 'utf8';   // データベースの文字コード
 
    // MySQL用のDSN文字列
    $dsn = 'mysql:dbname='.$dbname.';host='.$host.';charset='.$charset;
 
    try {
      // データベースに接続
      $dbh = new PDO($dsn, $username, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4'));
      $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
 
      // SQL文を作成
      $sql = 'select * from test_post';
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
  </body>
</html>
