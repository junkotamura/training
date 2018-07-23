<?php
//設定ファイル読み込み
require_once '../const.php';
//間数ファイル読み込み
require_once '../model/finish_model.php';

$data = array();
$sum = '';
$err_msg = array();

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
    $data = select_cart_item($dbh, $user_id);
  
    //金額と数量データを取得
    $cart_price = select_cart_price($dbh, $user_id);
    var_dump($cart_price);
    //金額データをリスト化
    $price_list = array_column($cart_price, "price");
    
    //数量データをリスト化
    $amount_list = array_column($cart_price, "amount");

    //２つのリストを乗算
    $sum_list = sum_calc('calc', $price_list, $amount_list);
    
    //リストの合計金額を計算 
    $sum = array_sum($sum_list);
    
    //特殊文字をHTMLエンティティに変換
    $data = entity_assoc_array($data);
    
} catch (Exception $e) {
    $err_msg[] = $e->getMessage();
  }



include_once '../view/finish_view.php';
