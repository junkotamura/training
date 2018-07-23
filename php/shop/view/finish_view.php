<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <title>購入完了ページ</title>
    <style>
    .content {
      margin-left: auto;
      margin-right: auto;
      width: 960px;
    }
    ul {
      list-style: none;
    }
    .thankyou {
      font-size: 1.5em;
      text-align: center;
      height:60px;
      line-height: 60px;
      background-color: #FFF2DC;
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
      margin-left: 540px;
      color: #FF0000;
    }
    .cart-list .cart-item-amount {
      position: absolute;
      margin-left: 740px;
    }
    .sum {
      margin-left: 630px;
    }
    .sum-price {
      margin-left: 30px;
      font-size: 1.5em;
      color: #FF0000;
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
      <div class="thankyou">ご購入ありがとうございました</div>
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
                <span class="cart-item-price">
                  ¥ <?php echo htmlspecialchars($value['price']); ?>
                </span>
                <span class="cart-item-amount">
                  <?php echo htmlspecialchars($value['amount']); ?>
                </span>
              </div>
            </li>
          </ul>
        <?php } ?>
      </div>
      <div>
        <span class="sum">合計</span>
        <span class="sum-price">¥<?php echo htmlspecialchars("$sum"); ?></span>
      </div>
      
    </div>
  </body>
</html>
