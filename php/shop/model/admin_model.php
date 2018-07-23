<?php

date_default_timezone_set('Asia/Tokyo');
$date = date('Y-m-d H:i:s');
$up_date = '';
$name = '';
$price = '';
$stock = '';
$status = '';
$item_id = '';

$img_dir    = '../img/';    // アップロードした画像ファイルの保存ディレクトリ
$new_img_filename = '';   // アップロードした新しい画像ファイル名
$data       = array();
$err_msg    = array();
$msg        = '';


// アップロード画像ファイルの保存
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (isset($_POST['submit1'])) {
         
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
	
function validation($data) {
    
    $err_msg = array();
    
	if (isset($_POST['item_name'])) {
	    
	    $name = get_post_data('item_name');
	    if($name === '') {
	    
		    $err_msg[] = '名前を入力してください。';
	    }
	}
	
	if (isset($_POST['price'])) {
	    
	    $price = get_post_data('price');
	    if ($price === '') {
        
            $err_msg[] = '値段を入力してください。';
        }
        
        if(ctype_digit(strval($price)) === FALSE && empty($price) === FALSE) {
        
            $err_msg[] = '値段は半角数字を入力してください。';
        }
	}
	
	if (isset($_POST['stock'])) {
	    
	    $stock = get_post_data('stock');
        if ($stock === '') {
        
            $err_msg[] = '個数を入力してください。';
        }
        
        if(ctype_digit(strval($stock)) === FALSE && empty($stock) === FALSE) {
        
            $err_msg[] = '個数は半角数字を入力してください。';
        }
	}
    
    if (isset($_POST['status'])) {
        
        $status = get_post_data('status');
        if ($status === '') {
        
            $err_msg[] = '公開ステータスを選択してください。';
        }
    }
	return $err_msg;
}

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
* 商品登録を実行する
*
* @param obj $dbh DBハンドル
* @param str $sql SQL文
* @return bool 真偽のどちらか
*/
function insert_db($dbh, $sql) {

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
* 商品情報をデータベースに登録する（INSERT）
*
* @param obj $dbh DBハンドル
* @param str $name 商品名
* @param int $price 値段
* @param int $new_img_filename 画像名
* @param int $status ステータス
* @param int $date 日付
* @return bool
*/
function insert_item_master($dbh, $name, $price, $new_img_filename, $status, $date) {
    
    $name = get_post_data('item_name');
	$price = get_post_data('price');
	$status = get_post_data('status');
 
    // SQL生成
    $sql = 
        "INSERT INTO ec_item_master
          (name,
           price,
           img,
           status,
           create_datetime,
           update_datetime) 
        VALUES 
          ('$name',
           '$price',
           '$new_img_filename',
           '$status',
           '$date',
           '$date');";
    // クエリ実行
    return insert_db($dbh, $sql);
}

/**
* 商品情報をデータベースに登録する（INSERT）
*
* @param obj $dbh DBハンドル
* @param int $stock 在庫
* @param int $date 日付
* @return bool
*/
function insert_item_stock($dbh, $stock, $date) {
    
	$stock = get_post_data('stock');
 
    // SQL生成
    $sql = 
        "INSERT INTO ec_item_stock
          (stock,
           create_datetime,
           update_datetime) 
        VALUES 
          ('$stock',
           '$date',
           '$date');";
                  
    // クエリ実行
    return insert_db($dbh, $sql);
}

/**
* クエリを実行しその結果を配列で取得する
*
* @param obj $dbh DBハンドル
* @param str $sql SQL文
* @return array 結果配列データ
*/
function get_as_array($dbh, $sql) {

    try {
        //SQL文を実行する準備
        $stmt = $dbh->prepare($sql);
        //SQLの実行
        $stmt->execute();
        //レコードの取得
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
* 商品情報一覧を取得する（SELECT）
*
* @param obj $dbh DBハンドル
* @return array 商品一覧配列データ
*/
function get_item_list($dbh) {
 
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
    return get_as_array($dbh, $sql);
}

/**
* ステータス変更を実行する
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
* 商品情報のステータスを変更する（UPDATE）
*
* @param obj $dbh DBハンドル
* @param int $status ステータス
* @param int $up_date 日付
* @param int $item_id 商品ID
* @return bool
*/
function update_item_master($dbh, $status, $up_date, $item_id) {

    $status = get_post_data('status');
    $up_date = get_post_data('update_datetime');
    $item_id = get_post_data('item_id');
    
    // SQL生成
    $sql =
        "UPDATE
           ec_item_master
        SET
          status = '$status',
          update_datetime = '$up_date',
          status =
            (CASE WHEN status = 0 THEN 1 WHEN status = 1 THEN 0 ELSE status END )
        WHERE
           item_id = '$item_id';";
                  
    // クエリ実行
    return change_db($dbh, $sql);
}

/**
* 在庫数を変更する（UPDATE）
*
* @param obj $dbh DBハンドル
* @param int $stock 在庫数
* @param int $up_date 日付
* @param int $item_id 商品ID
* @return bool
*/
function update_item_stock($dbh, $stock, $up_date, $item_id) {

    $stock = get_post_data('stock');
    $up_date = get_post_data('update_datetime');
    $item_id = get_post_data('item_id');
    
    // SQL生成
    $sql = 
        "UPDATE
          ec_item_stock
        SET
          stock = '$stock',
          update_datetime = '$up_date'
        WHERE
          item_id = '$item_id';";
          
    // クエリ実行
    return change_db($dbh, $sql);
}

/**
* 商品を削除する（DELETE）
*
* @param obj $dbh DBハンドル
* @param int $item_id 商品ID
* @return bool
*/
function delete_item($dbh, $item_id) {

    $item_id = get_post_data('item_id');
    
    // SQL生成
    $sql = 
        "DELETE
          ec_item_master,
          ec_item_stock
        FROM
          ec_item_master
        LEFT JOIN
          ec_item_stock
        ON
          ec_item_master.item_id = ec_item_stock.item_id
        WHERE
          ec_item_master.item_id = '$item_id';";
          
    // クエリ実行
    return change_db($dbh, $sql);
}
?>
