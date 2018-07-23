<?php
$user_name = '';
$db_password = '';
$user_id ='';
$err_msg    = array();

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
* ユーザー名のチェックを実行する
*
* @param obj $dbh DBハンドル
* @param str $sql SQL文
* @return str $count_name
*/
function check_name($dbh, $sql) {
    
    try {
        //SQL文を実行する準備
        $stmt = $dbh->prepare($sql);
        //SQLの実行
        $stmt->execute();
        //結果の取得
        $res = $stmt->fetchColumn();
        $count_name = $res['cnt'];
        
    } catch (PDOException $e) {
        throw $e;
      }
    return $count_name;
}

/**
* ユーザー名がデータベースにあるかチェック（SELECT）
*
* @param obj $dbh DBハンドル
* @param str $user_name ユーザー名
* @return array 結果配列データ
*/
function login_user($dbh, $user_name) {

    $user_name = get_post_data('user_name');

    // SQL生成
    $sql =
        "SELECT COUNT(*)
        AS
          cnt
        FROM
          ec_user
        WHERE
          user_name = '$user_name';";
    // クエリ実行
    return check_name($dbh, $sql);
}

/**
* パスワード取得を実行する
*
* @param obj $dbh DBハンドル
* @param str $sql SQL文
* @return str $db_password パスワード 
*/
function check_pw($dbh, $sql) {
    
    try {
        //SQL文を実行する準備
        $stmt = $dbh->prepare($sql);
        //SQLの実行
        $stmt->execute();
        //結果の取得
        $row = $stmt->fetch(); 
        $array[] = $row;
        $db_password = $array[0]['password'];
        
    } catch (PDOException $e) {
        throw $e;
      }
    return $db_password;
}

/**
* 登録しているパスワードを取得（SELECT）
*
* @param obj $dbh DBハンドル
* @param str $user_name ユーザー名
* @return array 結果配列データ
*/
function login_pw($dbh, $user_name) {
    
    $user_name = get_post_data('user_name');

    // SQL生成
    $sql =
        "SELECT
          password
        FROM
          ec_user
        WHERE
          user_name = '$user_name';";
    // クエリ実行
    return check_pw($dbh, $sql);
}

/**
* ユーザID取得を実行する
*
* @param obj $dbh DBハンドル
* @param str $sql SQL文
* @return str $user_id ユーザID 
*/
function check_user_id($dbh, $sql) {
    
    try {
        //SQL文を実行する準備
        $stmt = $dbh->prepare($sql);
        //SQLの実行
        $stmt->execute();
        //結果の取得
        $row = $stmt->fetch(); 
        $array[] = $row;
        $user_id = $array[0]['user_id'];
        
    } catch (PDOException $e) {
        throw $e;
      }
    return $user_id;
}

/**
* ユーザIDを取得（SELECT）
*
* @param obj $dbh DBハンドル
* @param str $user_name ユーザー名
* @return array 結果配列データ
*/
function select_id($dbh, $user_name) {
    
    $user_name = get_post_data('user_name');

    // SQL生成
    $sql =
        "SELECT
          user_id
        FROM
          ec_user
        WHERE
          user_name = '$user_name';";
    // クエリ実行
    return check_user_id($dbh, $sql);
}

