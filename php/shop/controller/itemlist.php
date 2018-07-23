<?php
//設定ファイル読み込み
require_once '../const.php';
//間数ファイル読み込み
require_once '../model/itemlist_model.php';

$data = array();
$err_msg    = array();

session_start();
// セッション変数からuser_id取得
if (isset($_SESSION['user_id'])) {
    
  $user_id = $_SESSION['user_id'];
} else {
  // 非ログインの場合、ログインページへリダイレクト
  header('Location:login.php');
  exit;
}

try {
    //DB接続
    $dbh = get_db_connect();
    
    //商品一覧データを取得
    $data = select_item($dbh);
    
    //特殊文字をHTMLエンティティに変換
    $data = entity_assoc_array($data);
    
} catch (Exception $e) {
    $err_msg[] = $e->getMessage();
  }

if (isset($_POST['cart_item_in'])) {

    try {
        //DB接続
        $dbh = get_db_connect();
        
        //カート内に同一商品があるかチェック
        $count_item = count_item($dbh, $user_id, $item_id);
        
        if ($count_item == 0) {
    
            //ステータスの変更
            $data = insert_item($dbh, $user_id, $item_id, $date);
    
            //商品一覧データを取得
            $data = select_item($dbh);
        
            //特殊文字をHTMLエンティティに変換
            $data = entity_assoc_array($data);
        
            //メッセージを表示
            $msg = 'カートに登録しました。';
        } else {
            
            //同一商品の数を増やす
            $data = update_cart_item($dbh, $user_id, $item_id, $date);
          
            //商品一覧データを取得
            $data = select_item($dbh);
        
            //特殊文字をHTMLエンティティに変換
            $data = entity_assoc_array($data);
        
            //メッセージを表示
            $msg = 'カートに登録しました。';
          }
    } catch (Exception $e) {
        $err_msg[] = $e->getMessage();
      }
}

include_once '../view/itemlist_view.php';
