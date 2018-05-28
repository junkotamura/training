<?php
$filename = './review.txt';
$name = 0;
$comment = 0;
$name_max = 20;
$comment_max = 100;
date_default_timezone_set('Asia/Tokyo');
$log = date('Y-m-d H:i:s');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['name'];
  $comment = $_POST['comment'];

  if (($fp_name = fopen($filename , 'a')) !== FALSE) {
    if (((mb_strlen($name) <= $name_max) && (mb_strlen($name) !== 0)) && ((mb_strlen($comment) <= $comment_max) && (mb_strlen($comment) !== 0))) {
      if (fwrite($fp_name, "$name:") === FALSE) {
          print 'ファイル書き込み失敗' . $filename;
      }
    }
    fclose($fp_name);
  }

  $text = "$comment $log\n";
  if (($fp_comment = fopen($filename, 'a')) !== FALSE) {
    if (((mb_strlen($comment) <= $comment_max) && (mb_strlen($comment) !== 0)) && ((mb_strlen($name) <= $name_max) && (mb_strlen($name) !== 0))) {
      if (fwrite($fp_comment, "$text") === FALSE) {
          print 'ファイル書き込み失敗' . $filename;
      }
    }
    fclose($fp_comment);
  } 
}

$data = array();

  if (is_readable($filename) === TRUE) {
      if (($fp = fopen($filename,'r')) !== FALSE) {
           while(($tmp = fgets($fp)) !== FALSE) {
           $data[] = htmlspecialchars($tmp, ENT_QUOTES, 'UTF-8');
           }
           fclose($fp);
      }
  } else {
   $data[] = 'ファイルがありません';
  }
?>

<!DOCTYPE html>
<html lamg="ja">
<head>
  <meta charset="UTF-8">
  <title>ひとこと掲示板</title>
</head>
<body>
  <h1>ひとこと掲示板</h1>
  <?php if (mb_strlen($name) > $name_max) { ?>
    <ul>
      <li>
  <?php print '名前は20文字以内で入力してください'; ?>
      </li>
    </ul>
  <?php } ?>
  <?php if (mb_strlen($name) === 0) { ?>
    <ul>
      <li>
  <?php print '名前を入力してください'; ?>
      </li>
    </ul>
  <?php } ?>
  <?php if (mb_strlen($comment) > $comment_max) { ?>
    <ul>
      <li>
  <?php print 'ひとことは100文字以内で入力してください'; ?>
      </li>
    </ul>
  <?php } ?>
  <?php if (mb_strlen($comment) === 0) { ?>
    <ul>
      <li>
  <?php print 'ひとことを入力してください'; ?>
      </li>
    </ul>
  <?php } ?>
  <form method="post">
    名前：<input type="text" name="name" value="">
    ひとこと：<input type="text" name="comment" size= "50" value="">
    <input type="submit" name="submit" value="送信">
  </form>
  <?php foreach ($data as $read) { ?>
    <ul>
      <li>
  <?php print $read; ?>
      </li>
  　</ul>
  <?php } ?>
</body>
</html>
