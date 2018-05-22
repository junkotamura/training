<?php
$filename = './challenge_log.txt';
date_default_timezone_set('Asia/Tokyo');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comment = $_POST['comment']."\n";
    $time = date("n月j日 H:i:s"); 
    $write =$time. ' ' .$comment. "<br/>"; 
    $log = fopen ($filename, "a"); 
    flock ($log, LOCK_EX); 
    fputs ($log,$write); 
    flock ($log, LOCK_UN); 
    fclose ($log);
  }
$contents = file_get_contents($filename);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>ファイル操作</title>
</head>
<body>
  <h1>課題</h1>
  <form method="post">
    発言：<input type="text" name="comment">
    <input type="submit" name="submit" value="送信">
  </form>
  <p>発言一覧</p>
  <p><?php print $contents; ?></p>
</body>
</html>
