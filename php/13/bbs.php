<?php
$filename = 'review.txt';
date_default_timezone_set('Asia/Tokyo');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['my_name']."\n";
  $comment = $_POST['comment']."\n";

  if (!strlen($name) > 20) {
    $err_msg[] = '名前は20文字以内で入力してください';
  }
  if ($name === "" || $comment === "") {
      

  $time = date("Y/m/d H:i:s") . "\n";
  $write = '・' .$name. '：' .$comment. '-' .$time."<br/>";
  $log = fopen ($filename, "a");
  flock ($log, LOCK_EX);
  fputs ($log, $write);
  flock ($log, LOCK_UN);
  fclose ($log);
}
$contents = file_get_contents($filename);
?>

<!DOCTYPE html>
<html lamg="ja">
<head>
  <meta charset="UTF-8">
  <title>bbs</title>
</head>
<body>
  <h1>ひとこと掲示板</h1>
  <form method="post">
    名前：<input type="text" name="my_name">
    ひとこと：<input type="text" name="comment">
    <input type="submit" name="submit" value="送信"></label>
  </form>
  <p><?php print $contents; ?></p>
</body>
</html>
