<?php
date_default_timezone_set('Asia/Tokyo');
$date = date('Y-m-d H:i:s');
$item_id = '';
$img_dir    = '../img/';    // アップロードした画像ファイルの保存ディレクトリ
$new_img_filename = '';   // アップロードした新しい画像ファイル名
$data       = array();
$err_msg    = array();
$msg        = '';

/**
* POSTデータを取得
* @param str $key 配列キー
* @return str POST値
*/
function get_post_data($key) {

    $str = 0;
    if (isset($_POST[$key]) === true) {
        
        $str = $_POST[$key];
    }
    return $str;
}

/**
* 特殊文字をHTMLエンティティに変換する
* @param str $str 変換前文字
* @return str 変換後文字
*/
function entity_str($str) {

    return htmlspecialchars($str, ENT_QUOTES, HTML_CHARACTER_SET);
}

/**
* 特殊文字をHTMLエンティティに変換する（2次元配列の値）
* @param array $assoc_array 変換前配列
* @return array 変換後配列
*/
function entity_assoc_array($assoc_array) {

    foreach ($assoc_array as $key => $value) {
        foreach ($value as $keys => $values) {
          //特殊文字をHTMLエンティティに変換
          $assoc_array[$key][$keys] = entity_str($values);
        }
    }
    return $assoc_array;
}

/**
* DBハンドルを取得
* @return obj $dbh DBハンドル
*/
function get_db_connect() {

    try {
        //データベースに接続
        $dbh = new PDO(DSN, DB_USER, DB_PASSWD, array(PDO::MYSQL_ATTR_INIT_COMMAND =>DB_CHARSET));
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    } catch (PDOException $e) {
        throw $e;
      }
    return $dbh;
}

/**
* 商品情報一覧取得を実行する
*
* @param obj $dbh DBハンドル
* @param str $sql SQL文
* @return array 結果配列データ
*/
function get_item($dbh, $sql) {
    
    try {
        //SQL文を実行する準備
        $stmt = $dbh->prepare($sql);
        //SQLの実行
        $stmt->execute();
        //結果の取得
        $rows = $stmt->fetchAll();
        // 1行ずつ結果を配列で取得
        foreach ($rows as $row) {
            $data[] = $row;
        }
    } catch (PDOException $e) {
        throw $e;
      }
    return $rows;
}

/**
* 商品情報一覧を取得（SELECT）
*
* @param obj $dbh DBハンドル
* @return array 結果配列データ
*/
function select_item($dbh) {

    // SQL生成
    $sql = 
    "SELECT 
      ec_item_master.item_id,
      ec_item_master.name,
      ec_item_master.price,
      ec_item_master.img,
      ec_item_master.status,
      ec_item_master.create_datetime,
      ec_item_master.update_datetime,
      ec_item_stock.stock
    FROM
      ec_item_master
    INNER JOIN
      ec_item_stock
    ON
      ec_item_master.item_id = ec_item_stock.item_id
    AND
      ec_item_master.create_datetime = ec_item_stock.create_datetime";
       
    // クエリ実行
    return get_item($dbh, $sql);
}

/**
* 同一商品のチェックを実行する
*
* @param obj $dbh DBハンドル
* @param str $sql SQL文
* @return str $count_item
*/
function check_item($dbh, $sql) {
    
    try {
        //SQL文を実行する準備
        $stmt = $dbh->prepare($sql);
        //SQLの実行
        $stmt->execute();
        //結果の取得
        $res = $stmt->fetchColumn();
        $count_item = $res['cnt'];
        
    } catch (PDOException $e) {
        throw $e;
      }
    return $count_item;
}

/**
* 同一商品がカート内にあるかチェック（SELECT）
*
* @param obj $dbh DBハンドル
* @param str $user_id ユーザーID
* @param int $item_id 商品ID
* @return array 結果配列データ
*/
function count_item($dbh, $user_id, $item_id) {

    $user_id = get_post_data('user_id');
    $item_id = get_post_data('item_id');

    // SQL生成
    $sql =
        "SELECT COUNT(*)
        AS
          cnt
        FROM
          ec_cart
        WHERE
          item_id = '$item_id'
        AND
          user_id = '$user_id';";
    // クエリ実行
    return check_item($dbh, $sql);
}


/**
* ステータス変更を実行する
*
* @param obj $dbh DBハンドル
* @param str $sql SQL文
* @return bool 真偽のどちらか
*/
function cart_db($dbh, $sql) {

    try {
        // SQL文を実行する準備
        $stmt = $dbh->prepare($sql);
        // SQLを実行
        $stmt->execute();
    } catch (PDOException $e) {
        throw $e;
      }
}

/**
* カートに入れた商品を登録する（INSERT）
*
* @param obj $dbh DBハンドル
* @param int $user_id ユーザID
* @param int $item_id 商品ID
* @param int $date 日付
* @return bool
*/
function insert_item($dbh, $user_id, $item_id, $date) {

    $item_id = get_post_data('item_id');
    $date = get_post_data('update_datetime');
    
    // SQL生成
    $sql =
        "INSERT INTO ec_cart
          (user_id, item_id, amount, create_datetime, update_datetime)
        VALUES
          ('$user_id',
           '$item_id',
           1,
           '$date',
           '$date');";
                  
    // クエリ実行
    return cart_db($dbh, $sql);
}

/**
* 商品数変更を実行する
*
* @param obj $dbh DBハンドル
* @param str $sql SQL文
* @return bool 真偽のどちらか
*/
function change_db($dbh, $sql) {

    try {
        // SQL文を実行する準備
        $stmt = $dbh->prepare($sql);
        // SQLを実行
        $stmt->execute();
    } catch (PDOException $e) {
        throw $e;
      }
}

/**
* カート内の商品数を変更する（UPDATE）
*
* @param obj $dbh DBハンドル
* @param int $user_id ユーザID
* @param int $item_id 商品ID
* @param int $date 日付
* @return bool
*/
function update_cart_item($dbh, $user_id, $item_id, $date) {

    $item_id = get_post_data('item_id');
    
    // SQL生成
    $sql = 
        "UPDATE
          ec_cart
        SET
          amount = amount + 1,
          update_datetime = '$date'
        WHERE
          item_id = '$item_id'
        AND
          user_id = '$user_id';";
                  
    // クエリ実行
    return change_db($dbh, $sql);
}

?>
