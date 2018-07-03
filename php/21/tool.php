<?php
date_default_timezone_set('Asia/Tokyo');
$date = date('Y-m-d H:i:s');
$name = '';
$price = '';
$stock = '';
$status = '';
$id = '';

$host     = 'localhost';
$username = 'codecamp22362';   // MySQLのユーザ名
$password = 'WIOCLRNA';       // MySQLのパスワード
$dbname   = 'codecamp22362';    // MySQLのDB名(このコースではMySQLのユーザ名と同じです）
$charset  = 'utf8';   // データベースの文字コード

// MySQL用のDSN文字列
$dsn = 'mysql:dbname='.$dbname.';host='.$host.';charset='.$charset;
$sqls = 'SELECT 
          drink_master.drink_id,
          drink_master.drink_name,
          drink_master.price,
          drink_master.img,
          drink_master.status,
          drink_master.create_datetime,
          drink_master.update_datetime,
          drink_stock.stock
        FROM
          drink_master
        INNER JOIN
          drink_stock
        ON
          drink_master.drink_id = drink_stock.drink_id
        AND
          drink_master.create_datetime = drink_stock.create_datetime';
    

$img_dir    = './img/';    // アップロードした画像ファイルの保存ディレクトリ
$new_img_filename = '';   // アップロードした新しい画像ファイル名
$data       = array();
$err_msg    = array();
$msg        = '';

// アップロード画像ファイルの保存
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
     if (isset($_POST['submit1'])) {
         
        $name  = $_POST['drink_name'];
        $price = $_POST['price'];
        $stock = $_POST['stock'];
        $status = $_POST['status'];
    
        if ($name === '') {
        
            $err_msg[] = '名前を入力してください。';
        }
        if ($price === '') {
        
            $err_msg[] = '値段を入力してください。';
        }
        if ($stock === '') {
        
            $err_msg[] = '個数を入力してください。';
        }
        if(ctype_digit(strval($price)) === FALSE && empty($price) === FALSE) {
        
            $err_msg[] = '値段は半角数字を入力してください。';
        }
        if(ctype_digit(strval($stock)) === FALSE && empty($stock) === FALSE) {
        
            $err_msg[] = '個数は半角数字を入力してください。';
        }
        if ($status === '') {
        
            $err_msg[] = '公開ステータスを選択してください。';
        }
        
        // HTTP POST でファイルがアップロードされたかどうかチェック
        if (is_uploaded_file($_FILES['new_img']['tmp_name']) === TRUE) {
      
            // 画像の拡張子を取得
            $extension = pathinfo($_FILES['new_img']['name'], PATHINFO_EXTENSION);
            // 指定の拡張子であるかどうかチェック
            if ($extension === 'jpg' || $extension === 'jpeg' || $extension === 'JPEG' || $extension === 'png'|| $extension === 'PNG') {
        
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
            $err_msg[] = 'ファイルを選択してください。';
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
         
                $dbh->beginTransaction();
                try {
                    // SQL文を作成
                    $sql = 'INSERT INTO
                             drink_master(img, drink_name, price, status, create_datetime, update_datetime)
                            VALUES
                             (:img, :drink_name, :price, :status, :create_datetime, :update_datetime);';
                    // SQL文を実行する準備
                    $stmt = $dbh->prepare($sql);
                    // SQL文のプレースホルダに値をバインド
                    $stmt->bindValue(':img', $new_img_filename, PDO::PARAM_STR);
                    $stmt->bindValue(':drink_name', $name, PDO::PARAM_STR);
                    $stmt->bindValue(':price', $price, PDO::PARAM_INT);
                    $stmt->bindValue(':status', $status, PDO::PARAM_INT);
                    $stmt->bindValue(':create_datetime', $date, PDO::PARAM_INT);
                    $stmt->bindValue(':update_datetime', $date, PDO::PARAM_INT);
                    // SQLを実行
                    $stmt->execute();
                    
                    // SQL文を作成
                    $sql = 'INSERT INTO
                             drink_stock(stock, create_datetime, update_datetime)
                            VALUES
                             (:stock, :create_datetime, :update_datetime);';
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
                header('Location:http://codecamp22362.lesson8.codecamp.jp//php/21/tool.php');
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
                
                if (ctype_digit($_POST['stock']) == FALSE) {
                    
                    $err_msg[] = '在庫数は半角数字を入力してください。';
                } else {
                
                    $stock = $_POST['stock'];
                    $up_date = $_POST['update_datetime'];
                    $id = $_POST['drink_id'];

                    $dbh->beginTransaction();
                    try {
                        // SQL文を作成
                        $sql = "UPDATE
                                  drink_stock
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
                                  drink_stock
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
                        $msg = '在庫変更成功';
                    } catch (PDOException $e) {
                        $dbh->rollback();
                        throw $e;
                      } 
                }
            }
            
            if (isset($_POST['change_status'])) {
                
                $status = $_POST['status'];
                $up_date = $_POST['update_datetime'];
                $id = $_POST['drink_id'];
        
                try {
                    // SQL文を作成
                    $sql = "UPDATE
                              drink_master
                            SET
                              status = :status,
                              update_datetime = :update_datetime,
                              status=
                                (CASE WHEN status = '0' THEN '1' WHEN status = '1' THEN '0' ELSE status END )
                            WHERE
                              drink_id = :drink_id";
                    $stmt = $dbh->prepare($sql);
                    // SQL文のプレースホルダに値をバインド
                    $stmt->bindValue(':status', $status, PDO::PARAM_INT);
                    $stmt->bindValue(':update_datetime', $up_date, PDO::PARAM_INT);
                    $stmt->bindValue(':drink_id', $id, PDO::PARAM_INT);
                    // SQL文を実行
                    $stmt->execute();
                    $msg = 'ステータス変更成功';
                } catch (PDOException $e) {
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
      caption {
        text-align: left;
      }
      .text_align_right {
        text-align: right;
      }
      .drink_name_width {
        width: 100px;
      }
      .input_text_width {
        width: 60px;
      }
      .status_false {
          background-color: #A9A9A9;
      }
      .img_size {
          height: 125px;
      }
    </style>
    </head>
    <body>
      <?php foreach ((array)$err_msg as $value) { ?>
        <p><?php print $value; ?></p>
      <?php } ?>
      <p><?php print $msg; ?></p>
      <h1>自動販売機管理ツール</h1>
      <section>
        <h2>新規商品追加</h2>
        <form method="post" enctype="multipart/form-data">
          <div>
            <label>
              名前：<input type="text" name="drink_name" value="" />
            </label>
          </div>
          <div>
            <label>
              値段：<input type="text" name="price" value="" />
            </label>
          </div>
          <div>
            <label>
              個数：<input type="text" name="stock" value="" />
            </label>
          </div>
          <div>
            <input type="file" name="new_img">
          </div>
          <div>
            <select name="status">
              <option value="">公開ステータスを選択してください</option>
              <option value="0">非公開</option>
              <option value="1">公開</option>
            </select>
          </div>
          <div>
            <input type="submit" name="submit1" value="商品を追加">
          </div>
        </form>
      </section>    
      <section>
      <h2>商品情報変更</h2>
      <table>
        <caption>商品一覧</caption>
          <tbody>         
            <tr>
              <th>商品画像</th>
              <th>商品名</th>
              <th>価格</th>
              <th>在庫数</th>
              <th>ステータス</th>
            </tr>
            <?php foreach ($data as $value)  { ?>
              <?php if(htmlspecialchars($value['status']==0)){ ?>
              <tr class="status_false">
              <?php } else if(htmlspecialchars($value['status']==1)) { ?>
              <tr>
              <?php } ?>
                <td><img class="img_size" src="<?php echo htmlspecialchars($img_dir.$value['img']); ?>"></td>
                <td><?php echo htmlspecialchars($value['drink_name']); ?></td>
                <td><?php echo htmlspecialchars($value['price']); ?>円</td>
                <td>
                  <form method="post">
                    <input type="text" class="input_text_width text_align_right" name="stock" value="<?php echo htmlspecialchars($value['stock']); ?>">
                    <input type="hidden" name="update_datetime" value="<?php echo htmlspecialchars("$date"); ?>">
                    <input type="hidden" name="drink_id" value="<?php echo htmlspecialchars($value['drink_id']); ?>">
                    <input type="submit" name="submit2" value="変更">
                  </form>
                </td>
                <td>
                  <form method="post">
                    <input type="submit" name="change_status" value="<?php if(htmlspecialchars($value['status']==1)){ echo "公開→非公開"; } else if(htmlspecialchars($value['status']==0)){ echo "非公開→公開"; } ?>">
                    <input type="hidden" name="status" value="<?php echo htmlspecialchars($value['status']); ?>">
                    <input type="hidden" name="update_datetime" value="<?php echo htmlspecialchars("$date"); ?>">
                    <input type="hidden" name="drink_id" value="<?php echo htmlspecialchars($value['drink_id']); ?>">
                  </form>
                </td>
              </tr>
            <?php } ?>
          </tbody> 
        </table>
      </section>
    </body>
</html>
