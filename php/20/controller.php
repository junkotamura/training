<?php
//設定ファイル読み込み
require_once './conf/setting.php';
//間数ファイル読み込み
require_once './model/model.php';

date_default_timezone_set('Asia/Tokyo');
$date = date('Y-m-d H:i:s');
$name = 0;
$comment = 0;
$name_max = 20;
$comment_max = 100;
$comment_data = array();
$err_msg = array();

if ( isset( $_POST['send'] ) === true ) {
    
    //POST値取得
    $name = get_post_data('name');
    $comment = get_post_data('comment');
}
    
//入力値チェック
if (mb_strlen($name) > $name_max) {
        
    $err_msg[] = '名前は20文字以内で入力してください';
}
    
if (mb_strlen($name) === 0) {
        
    $err_msg[] = '名前を入力してください';
}
    
if (mb_strlen($comment) > $comment_max) {
        
    $err_msg[] ='ひとことは100文字以内で入力してください';
}
    
if (mb_strlen($comment) === 0) {
        
    $err_msg[] = 'ひとことを入力してください';
}
    
try {
    //DB接続
    $dbh = get_db_connect();
    
    //コメントの一覧を取得
    $comment_data = get_comment_list($dbh);
    
    //特殊文字をHTMLエンティティに変換
    $comment_data = entity_assoc_array($comment_data);
    
} catch (Exception $e) {
    $err_msg[] = $e->getMessage();
  }
        
if(isset($_POST['name']) && isset($_POST['comment'])) {
        
    try {
        //DB接続
        $dbh = get_db_connect();
    
        //コメントを登録
        $comment_data = insert_comment_list($dbh, $name, $comment, $date);
    
        //コメントの一覧を取得
        $comment_data = get_comment_list($dbh);
    
        //特殊文字をHTMLエンティティに変換
        $comment_data = entity_assoc_array($comment_data);
    
    } catch (Exception $e) {
        $err_msg[] = $e->getMessage();
      }
}
    
//ひとこと掲示板テンプレートファイル読み込み
include_once './view/view.php';

