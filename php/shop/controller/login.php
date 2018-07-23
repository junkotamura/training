<?php
//設定ファイル読み込み
require_once '../const.php';
//間数ファイル読み込み
require_once '../model/login_model.php';

$data = array();
$err_msg    = array();

session_start();
if(isset($_POST['login'])) {
    
    $user_name = get_post_data('user_name');
    $password = get_post_data('password');
    
    try {
        //DB接続
        $dbh = get_db_connect();
        
        //ユーザー名が登録されているかチェック
        $count_name = login_user($dbh, $user_name);
        
        if($count_name > 0) {
             
            //パスワードが登録されているかチェック
            $db_password = login_pw($dbh, $user_name);
            
            if($password == $db_password) {
                
                //ユーザIDを取得
                $user_id = select_id($dbh, $user_name);
                
                $_SESSION['user_id'] = $user_id;
                header('Location:itemlist.php');
                exit(); 
            } else {
                $err_msg[] = 'ユーザー名あるいはパスワードが違います。';

              }
        } else {
                $err_msg[] = 'ユーザー名あるいはパスワードが違います。';

              }
    } catch (Exception $e) {
        $err_msg[] = $e->getMessage();
      }
}
include_once '../view/login_view.php';
