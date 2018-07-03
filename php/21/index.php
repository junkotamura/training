<?php
date_default_timezone_set('Asia/Tokyo');
$date = date('Y-m-d H:i:s');
$price = '';
$status = '';
$id = '';
$stock ='';

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

try {
    // データベースに接続
    $dbh = new PDO($dsn, $username, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4'));
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

                try {
                    $sql = 'SELECT 
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
                    // SQL文を実行する準備
                    $stmt = $dbh->prepare($sql);
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
        #flex {
            width: 800px;
        }
        div {
            display: block;
        }
        #flex .drink {
            //border: solid 1px;
            width: 200px;
            height: 210px;
            text-align: center;
            margin: 10px;
            float: left;
        }
        #flex span {
            display: block;
            margin: 3px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .img_size {
            height: 125px;
        }
        .red {
            color: #FF0000;
        }
        #submit {
            clear: both;
        }
    </style>
  </head>
  <body>
    <h1>自動販売機</h1>
    <form action="result.php" method="post" enctype="multipart/form-data">
      <div>
        金額
        <input type="text" name="money" value="">
        <input type="submit" value="購入">
      </div>
      <?php foreach ($data as $value) { ?>
        <?php if(htmlspecialchars($value['status'] == 1)){ ?>
          <div id="flex">
            <div class="drink">
              <span class="img_size">
                <img src="<?php echo htmlspecialchars($img_dir.$value['img']); ?>">
              </span>
              <span>
                <?php echo htmlspecialchars($value['drink_name']); ?>
              </span>
              <span>
                <?php echo htmlspecialchars($value['price']); ?>円
              </span>
              <?php if(htmlspecialchars($value['stock'] > 0)){ ?>
                <input type="radio" name="drink_id" value="<?php echo htmlspecialchars($value['drink_id']); ?>">
                <input type="hidden" name="update_datetime" value="<?php echo htmlspecialchars("$date"); ?>">
              <?php } else { ?>
                <span class="red">売り切れ</span>
              <?php } ?>
            </div>
          </div>
        <?php } ?>
      <?php } ?> 
    </form>
  </body>
</html>
