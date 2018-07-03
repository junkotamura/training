<?php
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
* 書き込みを実行し登録する
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
* ユーザー名とコメントを書き込みする（INSERT）
*
* @param obj $dbh DBハンドル
* @param str $name ユーザー名
* @param str $comment コメント
* @param int $date
* @return bool
*/
function insert_comment_list($dbh, $name, $comment, $date) {
 
    // SQL生成
    $sql = 
        'INSERT INTO post
          (user_name,
           user_comment,
           create_datetime) 
        VALUES 
          ("' .$name. '" ,"' .$comment. '" , "' .$date. '" );';
                  
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
    } catch (PDOException $e) {
        throw $e;
      }
    return $rows;
}

/**
* コメント一覧を取得する（SELECT）
*
* @param obj $dbh DBハンドル
* @return array 商品一覧配列データ
*/
function get_comment_list($dbh) {
 
    // SQL生成
    $sql =
        'SELECT
          id, user_name, user_comment, create_datetime
         FROM
          post
         ORDER BY
          create_datetime DESC';
    // クエリ実行
    return get_as_array($dbh, $sql);
}

