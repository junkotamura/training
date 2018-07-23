<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>商品管理ページ</title>
    <style>
      h2 {
        border-top: 1px solid;
        padding-top: 20px;
      }
      table {
        width: 800px;
        border-collapse: collapse;
      }
      table, tr, th, td {
        border: solid 1px;
        padding: 10px;
        text-align: center;
      }
      caption {
        text-align: left;
      }
      .text_align_right {
        text-align: right;
      }
      .drink_name_width {
        width: 100px;
      }
      .input_text_width {
        width: 60px;
      }
      .status_false {
          background-color: #A9A9A9;
      }
      .img_size {
          height: 125px;
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
      <h1>CodeSHOP 管理ページ</h1>
      <a href="http://codecamp22362.lesson8.codecamp.jp//php/shop/user_list.php">ユーザ管理ページ</a>
      <section>
        <h2>商品の登録</h2>
        <form method="post" enctype="multipart/form-data">
          <div>
            <label>
              商品名：<input type="text" name="item_name" value="" />
            </label>
          </div>
          <div>
            <label>
              値　段：<input type="text" name="price" value="" />
            </label>
          </div>
          <div>
            <label>
              個　数：<input type="text" name="stock" value="" />
            </label>
          </div>
          <div>
              商品画像：<input type="file" name="new_img">
          </div>
          <div>
            <select name="status">
              ステータス：
              <option value="">公開ステータスを選択してください</option>
              <option value="0">非公開</option>
              <option value="1">公開</option>
            </select>
          </div>
          <div>
            <input type="submit" name="submit1" value="商品を登録する">
          </div>
        </form>
      </section>
      <section>
      <h2>商品情報の一覧・変更</h2>
      <table>
          <tbody>         
            <tr>
              <th>商品画像</th>
              <th>商品名</th>
              <th>価格</th>
              <th>在庫数</th>
              <th>ステータス</th>
              <th>操作</th>
            </tr>
            <?php foreach ($data as $value)  { ?>
              <?php if(htmlspecialchars($value['status']==0)){ ?>
              <tr class="status_false">
              <?php } else if(htmlspecialchars($value['status']==1)) { ?>
              <tr>
              <?php } ?>
                <td><img class="img_size" src="<?php echo htmlspecialchars($img_dir.$value['img']); ?>"></td>
                <td><?php echo htmlspecialchars($value['name']); ?></td>
                <td><?php echo htmlspecialchars($value['price']); ?>円</td>
                <td>
                  <form method="post">
                    <input type="text" class="input_text_width text_align_right" name="stock" value="<?php echo htmlspecialchars($value['stock']); ?>">
                    <input type="hidden" name="update_datetime" value="<?php echo htmlspecialchars("$date"); ?>">
                    <input type="hidden" name="item_id" value="<?php echo htmlspecialchars($value['item_id']); ?>">
                    <input type="submit" name="change_stock" value="変更する">
                  </form>
                </td>
                <td>
                  <form method="post">
                    <input type="submit" name="change_status" value="<?php if(htmlspecialchars($value['status']==1)){ echo "公開→非公開にする"; } else if(htmlspecialchars($value['status']==0)){ echo "非公開→公開にする"; } ?>">
                    <input type="hidden" name="status" value="<?php echo htmlspecialchars($value['status']); ?>">
                    <input type="hidden" name="update_datetime" value="<?php echo htmlspecialchars("$date"); ?>">
                    <input type="hidden" name="item_id" value="<?php echo htmlspecialchars($value['item_id']); ?>">
                  </form>
                </td>
                <td>
                  <form method="post">
                    <input type="hidden" name="item_id" value="<?php echo htmlspecialchars($value['item_id']); ?>">
                    <input type="submit" name="delete_item" value="削除する">
                  </form>
                </td>
              </tr>
            <?php } ?>
          </tbody> 
        </table>
      </section>
  </body>
</html>
