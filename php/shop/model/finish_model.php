<?php
date_default_timezone_set('Asia/Tokyo');
$date = date('Y-m-d H:i:s');
$up_date = '';

$name = '';
$price = '';
$item_id = '';
$img_dir    = '../img/';    // アップロードした画像ファイルの保存ディレクトリ
$new_img_filename = '';   // アップロードした新しい画像ファイル名
$sum = 0;
$amount = '';
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
* カート内情報取得を実行する
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
* カート内情報を取得（SELECT）
*
* @param obj $dbh DBハンドル
* @param int $user_id ユーザID
* @return array 結果配列データ
*/
function select_cart_item($dbh, $user_id) {

    // SQL生成
    $sql = 
        "SELECT
          ec_item_master.item_id,
          ec_item_master.name,
          ec_item_master.price,
          ec_item_master.img,
          ec_cart.user_id,
          ec_cart.amount
        FROM
          ec_item_master
        INNER JOIN
          ec_cart
        ON
          ec_item_master.item_id = ec_cart.item_id
        WHERE
          ec_cart.user_id = '$user_id';";
       
    // クエリ実行
    return get_item($dbh, $sql);
}

/**
* 合計金額取得を実行する
*
* @param obj $dbh DBハンドル
* @param str $sql SQL文
* @return array 結果配列データ
*/
function sum_price($dbh, $sql) {
    
    try {
        //SQL文を実行する準備
        $stmt = $dbh->prepare($sql);
        //SQLの実行
        $stmt->execute();
        //結果の取得
        $rows = $stmt->fetchAll(); 
        
        foreach ($rows as $row) {
           $data[] = $row;
        }
    } catch (PDOException $e) {
        throw $e;
      }
    
    return $rows;
}

/**
* カート内の金額と数量を取得（SELECT）
*
* @param obj $dbh DBハンドル
* @param int $user_id ユーザID
* @return array 結果配列データ
*/
function select_cart_price($dbh, $user_id) {

    // SQL生成
    $sql = 
        "SELECT
          ec_item_master.price,
          ec_cart.amount
        FROM
          ec_item_master
        INNER JOIN
          ec_cart
        ON
          ec_item_master.item_id = ec_cart.item_id
        WHERE
          ec_cart.user_id = '$user_id';";
       
    // クエリ実行
    return sum_price($dbh, $sql);
}

/**
* データの一部を取得
* @param $list 値を取り出したい多次元配列
* @param str $key 配列キー
* @return array
*/
function array_column($list, $key) {

    $return_list = array();
        foreach ($list as $data) {
            
            $return_list[] = $data[$key];
        }
    return $return_list;
}

/**
* 配列の計算
* @param $list0 値を取り出したい多次元配列
* @param $list1 値を取り出したい多次元配列
* @param str $key 配列キー
* @return array
*/
function sum_calc($operator, $list0, $list1) {
    
    $output = array();
    foreach ($list0 as $key => $value) {
        if (array_key_exists($key, $list1)) {

            $output[$key] = $operator($list0[$key], $list1[$key]);
        }
    }
    return $output;
}

/**
* 配列の掛け算
* @param $i0 配列（$list0）のキー
* @param $i1 配列（$list1）のキー
* @return array
*/
function calc($i0, $i1) {
    
    return $i0 * $i1;
}

?>

