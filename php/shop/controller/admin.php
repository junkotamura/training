<?php
//設定ファイル読み込み
require_once '../const.php';
//間数ファイル読み込み
require_once '../model/admin_model.php';

$data = array();
$err_msg    = array();


try {
    //DB接続
    $dbh = get_db_connect();
    
    //商品一覧データを取得
    $data = get_item_list($dbh);
    
    //特殊文字をHTMLエンティティに変換
    $data = entity_assoc_array($data);

} catch (Exception $e) {
    $err_msg[] = $e->getMessage();
  }

if(isset($_POST['submit1'])) {

    $err_msg = validation($data);

    if (count($err_msg) === 0) {
        
        try {
            //DB接続
            $dbh = get_db_connect();
    
            //商品の登録
            $data = insert_item_master($dbh, $name, $price, $new_img_filename, $status, $date);
                    insert_item_stock($dbh, $stock, $date);
            //商品一覧データを取得
            $data = get_item_list($dbh);
            
            //特殊文字をHTMLエンティティに変換
            $data = entity_assoc_array($data);
        
            //メッセージを表示
            $msg = '商品を追加しました。';
        } catch (Exception $e) {
            $err_msg[] = $e->getMessage();
          }
    }
}

if (isset($_POST['change_stock'])) {
    
    $err_msg = validation($data);

    if (count($err_msg) === 0) {
        
        try {
            //DB接続
            $dbh = get_db_connect();
    
            //在庫数の変更
            $data = update_item_stock($dbh, $stock, $up_date, $item_id);
    
            //商品一覧データを取得
            $data = get_item_list($dbh);
        
            //特殊文字をHTMLエンティティに変換
            $data = entity_assoc_array($data);
    
            //メッセージを表示
            $msg = '在庫数を変更しました。';    
        } catch (Exception $e) {
            $err_msg[] = $e->getMessage();
          }
    }
}

if (isset($_POST['change_status'])) {

    try {
        //DB接続
        $dbh = get_db_connect();
    
        //ステータスの変更
        $data = update_item_master($dbh, $status, $up_date, $item_id);
    
        //商品一覧データを取得
        $data = get_item_list($dbh);
        
        //特殊文字をHTMLエンティティに変換
        $data = entity_assoc_array($data);
        
        //メッセージを表示
        $msg = 'ステータスを変更しました。';
    } catch (Exception $e) {
        $err_msg[] = $e->getMessage();
      }
}

if (isset($_POST['delete_item'])) {

    try {
        //DB接続
        $dbh = get_db_connect();
    
        //商品の削除
        $data = delete_item($dbh, $item_id);
    
        //商品一覧データを取得
        $data = get_item_list($dbh);
        
        //特殊文字をHTMLエンティティに変換
        $data = entity_assoc_array($data);
        
        //メッセージを表示
        $msg = '商品を削除しました。';
    } catch (Exception $e) {
        $err_msg[] = $e->getMessage();
      }
}

include_once '../view/admin_view.php';
