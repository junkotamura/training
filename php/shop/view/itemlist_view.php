<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>商品一覧ページ</title>
    <style>
    div {
      display: block;
    }
    .content {
      width: 960px;
     }
    .item-list li {
      float:left;
      margin-top: 20px;
      margin-left: 20px;
      width:200px;
    }
    .item-list .item {
      width: 200px;
      text-align: center;
      margin: 10px;
      float: left;
    }
    .item-list .item-img{
      width:200px;
    }
    .item-list .item-info{
      margin-top: 5px;
    }
    .item-list .item-name {
      float:left;
    }
    .item-list .item-price {
      display:block;
      float:right;
    }
    .item-list .cart-btn {
      display:block;
      clear:both;
      margin:0 auto;
      width:200px;
      height:40px;
      color: #ffffff;
      font-size: 100%;
      background-color: #00A7F7;
      border:none;
    }
    .item-list .sold-out{
      clear:both;
      height:40px;
      color: #FF0000;
      font-size: 1.2em;
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
    <h1>CodeCampSHOP</h1>
    <a href="http://codecamp22362.lesson8.codecamp.jp//php/shop/controller/cart.php">カートを見る</a>
    <a href="http://codecamp22362.lesson8.codecamp.jp//php/shop/user_list.php">ログアウト</a>
    <div class="content">
      <div class="item-list">
        <?php foreach ($data as $value)  { ?>
          <?php if(htmlspecialchars($value['status'] == 1)){ ?>
            <div class="item">
              <form action="./itemlist.php" method="post">
                <img class="item-img" src="<?php echo htmlspecialchars($img_dir.$value['img']); ?>">
                <div class="item-info">
                  <span class="item-name">
                    <?php echo htmlspecialchars($value['name']); ?>
                  </span>
                  <span class="item-price">
                    <?php echo htmlspecialchars($value['price']); ?>円
                  </span>
                </div>    
                <?php if(htmlspecialchars($value['stock'] > 0)){ ?>
                  <input type="hidden" name="item_id" value="<?php echo htmlspecialchars($value['item_id']); ?>">
                  <input type="hidden" name="user_id" value="<?php echo htmlspecialchars("$user_id"); ?>">
                  <input type="hidden" name="update_datetime" value="<?php echo htmlspecialchars("$date"); ?>">
                  <input class="cart-btn" type="submit" name="cart_item_in" value="カートに入れる">
                <?php } else { ?>
                  <span class="sold-out">売り切れ</span>
                <?php } ?>
              </form>
            </div>
          <?php } ?>
        <?php } ?>
      </div>
    </div>
  </body>
</html>
