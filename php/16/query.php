<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>query</title>
  </head>
  <body>
    <?php
    $host     = 'localhost';
    $username = 'codecamp22362';
    $password = 'WIOCLRNA';
    $dbname   = 'codecamp22362';
    $charset  = 'utf8';

    $dsn = 'mysql:dbname='.$dbname.';host='.$host.';charset='.$charset;

    try {
      //データベースに接続
      $dbh = new PDO($dsn, $username, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4'));
      $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION):
      $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
      // SQL文を作成
      $sql = 'select * from test_post';
      // SQLを実行
      $res = $dbh->query($sql);
      // レコードの取得
      $rows = $res->fetchAll();
      var_dump($rows);
    } catch (PDOException $e) {
      echo '接続できませんでした。理由：'.$e->getMessage();
    }
    ?>
  </body>
</html> 
