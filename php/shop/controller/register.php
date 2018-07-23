<?php
//設定ファイル読み込み
require_once '../const.php';
//間数ファイル読み込み
require_once '../model/register_model.php';

$data = array();
$err_msg = array();
$count = '';

if(isset($_POST['send'])) {

    $err_msg = validation($data);
    try {
        //DB接続
        $dbh = get_db_connect();
        //ユーザー名の重複チェック
        $count = check_user_name($dbh, $user_name);
        if($count > 0) {
            
            $err_msg = '同じユーザー名が既に登録されています。';
        }
        if (count($err_msg) === 0) {

            try {
                //DB接続
                $dbh = get_db_connect();
                //ユーザーの登録
                $data = register_user($dbh, $user_name, $password, $date);
                //メッセージを表示
                $msg = 'アカウント作成を完了しました。';
            } catch (Exception $e) {
                $err_msg[] = $e->getMessage();
              }
        }
    } catch (Exception $e) {
            $err_msg[] = $e->getMessage();
      }
}
include_once '../view/register_view.php';

