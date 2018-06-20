<?php
date_default_timezone_set('Asia/Tokyo');
$date = date('Y-m-d H:i:s');
$name = '';
$price = '';
$stock = '';
$drink_id = '';

$host     = 'localhost';
$username = 'codecamp22362';   // MySQLのユーザ名
$password = 'WIOCLRNA';       // MySQLのパスワード
$dbname   = 'codecamp22362';    // MySQLのDB名(このコースではMySQLのユーザ名と同じです）
$charset  = 'utf8';   // データベースの文字コード

// MySQL用のDSN文字列
$dsn = 'mysql:dbname='.$dbname.';host='.$host.';charset='.$charset;
$sqls = 'SELECT 
          test_drink_master.drink_id,
          test_drink_master.drink_name,
          test_drink_master.price,
          test_drink_master.img_file_name,
          test_drink_master.create_datetime,
          test_drink_stock.stock,
          test_drink_stock.update_datetime
        FROM
          test_drink_master
        INNER JOIN
          test_drink_stock
        ON
          test_drink_master.drink_id = test_drink_stock.drink_id
        AND
          test_drink_master.create_datetime = test_drink_stock.create_datetime';
    

$img_dir    = './img/';    // アップロードした画像ファイルの保存ディレクトリ
$new_img_filename = '';   // アップロードした新しい画像ファイル名
$data       = array();
$err_msg    = array();


// アップロード画像ファイルの保存
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
     if(isset($_POST['submit1'])) {
    
        // HTTP POST でファイルがアップロードされたかどうかチェック
        if (is_uploaded_file($_FILES['new_img']['tmp_name']) === TRUE) {
      
            // 画像の拡張子を取得
            $extension = pathinfo($_FILES['new_img']['name'], PATHINFO_EXTENSION);
            // 指定の拡張子であるかどうかチェック
            if ($extension === 'jpg' || $extension === 'jpeg' || $extension === 'png') {
        
                // 保存する新しいファイル名の生成（ユニークな値を設定する）
                $new_img_filename = sha1(uniqid(mt_rand(), true)). '.' . $extension;
                // 同名ファイルが存在するかどうかチェック
                if (is_file($img_dir . $new_img_filename) !== TRUE) {
          
                    // アップロードされたファイルを指定ディレクトリに移動して保存
                    if (move_uploaded_file($_FILES['new_img']['tmp_name'], $img_dir . $new_img_filename) !== TRUE) {
                        $err_msg[] = 'ファイルアップロードに失敗しました';
                    }
                } else {
                    $err_msg[] = 'ファイルアップロードに失敗しました。再度お試しください。';
                  }
            } else {
                $err_msg[] = 'ファイル形式が異なります。画像ファイルはJPEGまたはPNGのみ利用可能です。';
              }
        } else {
            $err_msg[] = 'ファイルを選択してください';
          }
    }
}

    try {
        // データベースに接続
        $dbh = new PDO($dsn, $username, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4'));
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

        if (count($err_msg) === 0 && $_SERVER['REQUEST_METHOD'] === 'POST') {
   
            if(isset($_POST['submit1'])) {
         
                $name  = $_POST['drink_name'];
                $price = $_POST['price'];
                $stock = $_POST['stock'];
    
                $dbh->beginTransaction();
                try {
                    // SQL文を作成
                    $sql = "INSERT INTO 
                              test_drink_master(img_file_name, drink_name, price, create_datetime)
                            VALUES
                              (:img_file_name, :drink_name, :price, :create_datetime);";
                    // SQL文を実行する準備
                    $stmt = $dbh->prepare($sql);
                    // SQL文のプレースホルダに値をバインド
                    $stmt->bindValue(':img_file_name', $new_img_filename, PDO::PARAM_STR);
                    $stmt->bindValue(':drink_name', $name, PDO::PARAM_STR);
                    $stmt->bindValue(':price', $price, PDO::PARAM_INT);
                    $stmt->bindValue(':create_datetime', $date, PDO::PARAM_INT);
                    // SQLを実行
                    $stmt->execute();
                    
                    // SQL文を作成
                    $sql = "INSERT INTO
                              test_drink_stock(stock, create_datetime, update_datetime)
                            VALUES
                              (:stock, :create_datetime, :update_datetime);";
                    // SQL文を実行する準備
                    $stmt = $dbh->prepare($sql);
                    // SQL文のプレースホルダに値をバインド
                    $stmt->bindValue(':stock', $stock, PDO::PARAM_INT);
                    $stmt->bindValue(':create_datetime', $date, PDO::PARAM_INT);
                    $stmt->bindValue(':update_datetime', $date, PDO::PARAM_INT);
                    // SQLを実行
                    $stmt->execute();
                    // コミット処理
                    $dbh->commit();
                } catch (PDOException $e) {
                    $dbh->rollback();
                    throw $e;
                  } 
                header('Location:http://codecamp22362.lesson8.codecamp.jp//php/manage.php'. $_SERVER[‘SCRIPT_NAME’]);
                exit;

                try {
                    // SQL文を実行する準備
                    $stmt = $dbh->prepare($sqls);
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
            }
        }
            
            if (isset($_POST['submit2'])) {
                
                $stock = $_POST['stock'];
                $up_date = $_POST['update_datetime'];
                $id = $_POST['drink_id'];

                $dbh->beginTransaction();
                try {
                    // SQL文を作成
                    $sql = "UPDATE
                              test_drink_stock
                            SET
                              update_datetime = :update_datetime
                            WHERE
                              drink_id = :drink_id";
                    // SQL文を実行する準備
                    $stmt = $dbh->prepare($sql);
                    // SQL文のプレースホルダに値をバインド
                    $stmt->bindValue(':update_datetime', $up_date, PDO::PARAM_INT);
                    $stmt->bindValue(':drink_id', $id, PDO::PARAM_INT);
                    // SQLを実行
                    $stmt->execute();
                    
                    // SQL文を作成
                    $sql = "UPDATE
                              test_drink_stock
                            SET
                              stock = :stock
                            WHERE
                              drink_id = :drink_id";
                     // SQL文を実行する準備
                    $stmt = $dbh->prepare($sql);
                    // SQL文のプレースホルダに値をバインド
                    $stmt->bindValue(':stock', $stock, PDO::PARAM_INT);
                    $stmt->bindValue(':drink_id', $id, PDO::PARAM_INT);
                    // SQLを実行
                    $stmt->execute();
                     // コミット処理
                    $dbh->commit();    
                } catch (PDOException $e) {
                    $dbh->rollback();
                    throw $e;
                  } 
            }
                try {
                    // SQL文を実行する準備
                    $stmt = $dbh->prepare($sqls);
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
?>

<html>
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>自動販売機</title>
    <style>
      h2 {
        border-top: 1px solid;
        padding-top: 20px;
      }
      table {
        width: 800px;
        border-collapse: collapse;
      }
      table, tr, th, td {
        border: solid 1px;
        padding: 10px;
        text-align: center;
      }
    </style>
    </head>
    <body>
      <h1>自動販売機管理ツール</h1>
      <h2>新規商品追加</h2>
      <?php foreach ((array)$err_msg as $value) { ?>
        <p><?php print $value; ?></p>
      <?php } ?>
        <form method="post" enctype="multipart/form-data">
          名前：<input type="text" name="drink_name" value="" /><br>
          値段：<input type="text" name="price" value="" /><br>
          個数：<input type="text" name="stock" value="" /><br>
          <div><input type="file" name="new_img"></div>
          <div><input type="submit" name="submit1" value="商品を追加"></div>
        </form>
      <h2>商品情報変更</h2>
        <p>商品一覧</p>
          <table>
            <tr>
              <th>商品画像</th>
              <th>商品名</th>
              <th>価格</th>
              <th>在庫数</th>
            </tr>
            <?php foreach ($data as $value)  { ?>
              <tr>
                <td><img src="<?php echo htmlspecialchars($img_dir . $value['img_file_name']); ?>"></td>
                <td><?php echo htmlspecialchars($value['drink_name']); ?></td>
                <td><?php echo htmlspecialchars($value['price']); ?>円</td>
                <td>
                  <form method="post">
                    <input type="text" name="stock" value="<?php echo htmlspecialchars($value['stock']); ?>">個
                    <input type="hidden" name="update_datetime" value="<?php echo htmlspecialchars("$date"); ?>">
                    <input type="hidden" name="drink_id" value="<?php echo htmlspecialchars($value['drink_id']); ?>">
                    <input type="submit" name="submit2" value="変更">
                  </form>
                </td>
              </tr>
            <?php } ?>
          </table>
    </body>
</html>
