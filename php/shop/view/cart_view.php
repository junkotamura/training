<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <title>ショッピングカートページ</title>
    <style>
    .content {
      margin-left: auto;
      margin-right: auto;
      width: 960px;
    }
    h1 {
      padding-top: 20px;
      padding-bottom: 20px;
    }
    ul {
      list-style: none;
    }
    .cart-list-title {
      clear: both;
      margin-top: 20px;
      margin-bottom: 10px;
      padding-bottom: 5px;
      border-bottom: 1px solid #ccc;
    }
    .cart-list-title .cart-list-price {
      margin-left: 700px;
    }
    .cart-list-title .cart-list-num {
      margin-left: 150px;
    }
    .cart-list .cart-item {
      clear: both;
      margin-top: 10px;
      margin-bottom: 10px;
      vertical-align: middle;
      border-bottom: 1px solid #ccc;
    }
    .cart-list .cart-item-img{
      width:120px;
    }
    .cart-list .cart-item-name{
      position: absolute;
      margin-left: 30px;
      width: 500px;
    }
    .cart-list .cart-item-price {
      position: absolute;
      margin-left: 560px;
      color: #FF0000;
    }
    .cart-item-del{
      display: inline;
      position: absolute;
      margin-left: 470px;
    }
    .cart-list .form_select_amount {
      float: right;
      margin-left: 50px;
    }
    .cart-list .cart-amount {
      width: 50px;
    }
    .sum {
      margin-left: 630px;
    }
    .sum-price {
      margin-left: 30px;
      font-size: 1.5em;
      color: #FF0000;
    }
    .buy {
      display:block;
      clear:both;
      margin:0 auto;
      margin-top: 30px;
      width:800px;
      height:60px;
      color: #ffffff;
      font-size: 140%;
      background-color: #F38C4F;
      border:none;
    }
    </style>
  </head>
  <body>
    <?php if (count($err_msg)) { ?>
      <?php foreach ((array)$err_msg as $value) { ?>
        <p><?php print $value; ?></p>
      <?php } ?>
    <?php } ?>
    <p><?php print $msg; ?></p>
    <div class="content">
      <h1>ショッピングカート</h1>
      <div class="cart-list-title">
        <span class="cart-list-price">価格</span>
        <span class="cart-list-num">数量</span>
      </div>
      <div>
        <?php foreach ($data as $value)  { ?>
        
          <ul class="cart-list">
            <li>
              <div class="cart-item">
        
                <img class="cart-item-img" src="<?php echo htmlspecialchars($img_dir.$value['img']); ?>">
                <span class="cart-item-name">
                  <?php echo htmlspecialchars($value['name']); ?>
                </span>
          
                <form class="cart-item-del" action="./cart.php" method="post">
                  <input type="submit" name="del_item" value="削除">
                  <input type="hidden" name="item_id" value="<?php echo htmlspecialchars($value['item_id']); ?>">
                </form>
          
                <span class="cart-item-price">
                  ¥ <?php echo htmlspecialchars($value['price']); ?>
                </span>
            
                <form class="form_select_amount" action="./cart.php" method="post">
                  <input type="text" class="cart-amount" name="amount" value="<?php echo htmlspecialchars($value['amount']); ?>">個
                  <input type="hidden" name="update_datetime" value="<?php echo htmlspecialchars("$date"); ?>">
                  <input type="hidden" name="item_id" value="<?php echo htmlspecialchars($value['item_id']); ?>">
                  <input type="submit" name="change_amount" value="変更する">
                </form>
          
              </div>
            </li>
          </ul>
        <?php } ?>
      </div>
      <div>
        <span class="sum">合計</span>
        <span class="sum-price">¥<?php echo htmlspecialchars("$sum"); ?></span>
      </div>
      <div>
        <form action="../controller/finish.php" method="post">
          <input type="hidden" name="user_id" value="<?php echo htmlspecialchars("$user_id"); ?>">
          <input class="buy" type="submit" name="buy_item" value="購入する">
        </form>
      </div>
    </div>
  </body>
</html>
