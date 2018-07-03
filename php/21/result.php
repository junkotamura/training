<?php
date_default_timezone_set('Asia/Tokyo');
$date = date('Y-m-d H:i:s');
$money = '';
$id = '';
$change = '';

$host     = 'localhost';
$username = 'codecamp22362';   // MySQLのユーザ名
$password = 'WIOCLRNA';       // MySQLのパスワード
$dbname   = 'codecamp22362';    // MySQLのDB名(このコースではMySQLのユーザ名と同じです）
$charset  = 'utf8';   // データベースの文字コード

// MySQL用のDSN文字列
$dsn = 'mysql:dbname='.$dbname.';host='.$host.';charset='.$charset;
$img_dir    = './img/';    // アップロードした画像ファイルの保存ディレクトリ
$data       = array();
$err_msg    = array();
$msg        = '';
    
//商品の選択と投入金額のチェック
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (!empty($_POST['drink_id']) === FALSE) {
        
        $err_msg[] = '商品を選択してください。';
    }
        
    if(isset($_POST['money'])) {
        
        if ($_POST['money'] === "") {
     
            $err_msg[] = 'お金を投入してください。';
        }
        
        if (ctype_digit($_POST['money']) === FALSE && $_POST['money'] !== "") {
     
            $err_msg[] = 'お金は半角数字を入力してください。';
        }
    }
}
    
if(count($err_msg) === 0 && $_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if(isset($_POST['money']) && isset($_POST['drink_id'])) {
        
        try {
            // データベースに接続
            $dbh = new PDO($dsn, $username, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4'));
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        
            $money = $_POST['money'];
            $id = $_POST['drink_id'];
            $up_date = $_POST['update_datetime'];
            
                try {
                    $sql = "SELECT 
                             drink_master.price
                            FROM
                             drink_master
                            WHERE
                             drink_id = :drink_id";
                    // SQL文を実行する準備
                    $stmt = $dbh->prepare($sql);
                    // SQL文のプレースホルダに値をバインド
                    $stmt->bindValue(':drink_id', $id, PDO::PARAM_INT);
                    // SQLを実行
                    $stmt->execute();
                    // レコードの取得
                    $row = $stmt->fetch(); 
                    $array[] = $row;
                    $price = $array[0]['price'];
                    
                    $change = $money - $price;
                    
                    //おつりが「文字列かつ数字」か、おつりが０円以上かチェック
                    if(ctype_digit(strval($change)) == TRUE && $change >= 0) {
                        
                        try {
                            $sql = "SELECT
                                     drink_stock.drink_id,
                                     stock,
                                     status
                                    FROM
                                     drink_stock
                                    INNER JOIN
                                     drink_master
                                    ON
                                     drink_stock.drink_id = drink_master.drink_id
                                    WHERE
                                     drink_stock.drink_id = :drink_id";
                            // SQL文を実行する準備
                            $stmt = $dbh->prepare($sql);
                            // SQL文のプレースホルダに値をバインド
                            $stmt->bindValue(':drink_id', $id, PDO::PARAM_INT);
                            // SQLを実行
                            $stmt->execute();
                            // レコードの取得
                            $row = $stmt->fetch(); 
                            $array[] = $row;
                            $stock = $array[1]['stock'];
                            $status = $array[1]['status'];
                        
                            //在庫が１個以上あり、ステータスが公開かチェック
                            if($stock > 0 && $status == 1) {
                        
                                //購入処理。在庫を減らし、履歴に登録
                                $dbh->beginTransaction();
                                try {
                                    // SQL文を作成
                                    $sql = "UPDATE
                                             drink_stock
                                            SET
                                             stock = stock-1,
                                             update_datetime = :update_datetime
                                            WHERE
                                             drink_id = :drink_id";
                                    $stmt = $dbh->prepare($sql);
                                    // SQL文のプレースホルダに値をバインド
                                    $stmt->bindValue(':update_datetime', $up_date, PDO::PARAM_INT);
                                    $stmt->bindValue(':drink_id', $id, PDO::PARAM_INT);
                                    // SQL文を実行
                                    $stmt->execute();

                                    // SQL文を作成
                                    $sql = 'INSERT INTO
                                             drink_history(drink_id,create_datetime)
                                            VALUES
                                             (:drink_id,:create_datetime);';
                                    // SQL文を実行する準備
                                    $stmt = $dbh->prepare($sql);
                                    // SQL文のプレースホルダに値をバインド
                                    $stmt->bindValue(':drink_id', $id, PDO::PARAM_INT);
                                    $stmt->bindValue(':create_datetime', $date, PDO::PARAM_INT);
                                    // SQLを実行
                                    $stmt->execute();
                                    // コミット処理
                                    $dbh->commit();
                                } catch (PDOException $e) {
                                    $dbh->rollback();
                                    throw $e;
                                  } 
                            } else {
                                $err_msg[] = '売り切れです！';
                              }
                        } catch (PDOException $e) {
                            throw $e;
                          }
                    } else {
                        $err_msg[] = 'お金がたりません！';
                      }
                } catch (PDOException $e) {
                    throw $e;
                  }
            //結果表示のためにデータ取得
            try {
                $sql = 'SELECT 
                         drink_master.drink_id,
                         drink_master.drink_name,
                         drink_master.price,
                         drink_master.img
                        FROM
                         drink_master
                        WHERE
                         drink_id = :drink_id';
                        // SQL文を実行する準備
                        $stmt = $dbh->prepare($sql);
                        // SQL文のプレースホルダに値をバインド
                        $stmt->bindValue(':drink_id', $id, PDO::PARAM_INT);
                        // SQLを実行
                        $stmt->execute();
                        // レコードの取得
                        $rows = $stmt->fetchAll();
                        // 1行ずつ結果を配列で取得
                        foreach ($rows as $row) {
                          $data[] = $row;
                        }
            } catch (PDOException $e) {
                throw $e;
              }
        } catch (PDOException $e) {
            $err_msg['db_connect'] = 'DBエラー：'.$e->getMessage();
          }
    }
}

?>
<html>
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>自動販売機</title>
  </head>
  <body>
    <h1>自動販売機</h1>
    <?php foreach ((array)$err_msg as $value) { ?>
      <p><?php print $value; ?></p>
    <?php } ?>
    <p><?php print $msg; ?></p>
    <?php foreach ($data as $value) { ?>
        <?php if(htmlspecialchars("$change" >= 0) && htmlspecialchars("$stock" > 0) && htmlspecialchars("$status" == 1)) { ?>
          <img src="<?php echo htmlspecialchars($img_dir.$value['img']); ?>">
          <p>がしゃん！【<?php echo htmlspecialchars($value['drink_name']); ?>】が買えました！</p>
          <p>おつりは【<?php echo htmlspecialchars("$change")?>円】です</p>
        <?php } ?>
    <?php } ?> 
    <footer>
      <a href="http://codecamp22362.lesson8.codecamp.jp//php/21/index.php">戻る</a>
    </footer>
 </body>
</html>
