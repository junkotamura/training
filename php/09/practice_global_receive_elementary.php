<!DOCTYPE HTML>
<html lang="ja">
<head>
   <meta charset="UTF-8">
   <title>課題</title>
</head>
<body>
<?php
if (isset($_POST['my_name']) === TRUE) {
  print 'ようこそ' . htmlspecialchars($_POST['my_name'], ENT_QUOTES, 'UTF-8') . 'さん';
} else if (isset($_POST['my_name']) == "e") {
  print '名前を入力してください';
}
?>
</body>
</html>
