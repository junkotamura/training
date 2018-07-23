<?php

date_default_timezone_set('Asia/Tokyo');
$date = date('Y-m-d H:i:s');
$user_name = '';
$password = '';
$err_msg    = array();
$err_msg2   = array();
$count = '';
$msg        = '';

function validation($data) {
    
    $err_msg = array();
    
	if (isset($_POST['user_name'])) {
	    
	    $user_name = $_POST['user_name'];
	    if ($user_name === '') {
	    
		    $err_msg[] = 'ユーザー名を入力してください。';
		    
		} else if (preg_match("/[^a-zA-Z0-9]+$/",$user_name)) {
	        
            $err_msg[] = 'ユーザー名は半角英数字を入力してください。';
          
	      } else if (preg_match("/^[a-zA-Z0-9]{1,5}$/",$user_name)) {
	          
            $err_msg[] = 'ユーザー名は6文字以上の文字を入力してください。';
            
	        }
	}
	
	if (isset($_POST['password'])) {
	    
	    $password = $_POST['password'];
	    
	    if($password === '') {
	    
		    $err_msg[] = 'パスワードを入力してください。';
		    
	    } else if (preg_match("/[^a-zA-Z0-9]+$/", $password)) {
	    
            $err_msg[] = 'パスワードは半角英数字を入力してください。';

	      } else if(preg_match("/^[a-zA-Z0-9]{1,5}$/", $password)) {
	        
            $err_msg[] = 'パスワードは6文字以上の文字を入力してください。';

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
* @return str $count 
*/
function check_db($dbh, $sql) {
    
    try {
        //SQL文を実行する準備
        $stmt = $dbh->prepare($sql);
        //SQLの実行
        $stmt->execute();
        //結果の取得
        $res = $stmt->fetchColumn();
        $count = $res['cnt'];
        
    } catch (PDOException $e) {
        throw $e;
      }
    return $count;
}

/**
* ユーザー名の重複をチェックする（SELECT）
*
* @param obj $dbh DBハンドル
* @param str $user_name ユーザー名
* @return array チェック結果配列データ
*/
function check_user_name($dbh, $user_name) {

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
    return check_db($dbh, $sql);
}

/**
* ユーザー登録を実行する
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
* ユーザー情報をデータベースに登録する（INSERT）
*
* @param obj $dbh DBハンドル
* @param str $user_name ユーザー名
* @param str $password パスワード
* @param int $date 日付
* @return bool
*/
function register_user($dbh, $user_name, $password, $date) {
    
	$user_name = get_post_data('user_name');
	$password = get_post_data('password');
 
    // SQL生成
    $sql = 
        "INSERT INTO ec_user
          (user_name,
           password,
           create_datetime,
           update_datetime) 
        VALUES 
          ('$user_name',
           '$password',
           '$date',
           '$date');";
                  
    // クエリ実行
    return insert_db($dbh, $sql);
}



