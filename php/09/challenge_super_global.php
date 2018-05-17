<?php
$my_name = '';
$gender = '';
$mail = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 if (isset($_POST['my_name']) === TRUE) {
    $my_name = htmlspecialchars($_POST['my_name'], ENT_QUOTES, 'UTF-8');
 if (isset($_POST['gender']) === TRUE) {
    $gender = htmlspecialchars($_POST['gender'], ENT_QUOTES, 'UTF-8');
 if (isset($_POST['mail']) === TRUE) {
    $mail = htmlspecialchars($_POST['mail'], ENT_QUOTES, 'UTF-8');
 }
 }
 } 
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>課題</title>
</head>
<body>
  <h1>課題</h1>
  <?php if ($my_name === $_POST['my_name']) { ?>
  <p>ここに入力したお名前を表示：<?php print $my_name; ?></p>
  <?php } ?>
  <?php if ($gender === 'man' || $gender === 'woman') { ?>
  <p>ここに選択した性別を表示：<?php print $gender; ?></p>
  <?php } ?>
  <?php if ($mail === $_POST['mail']) { ?>
  <p>ここにメールを受け取るかを表示：<?php print $mail; ?></p>
  <?php } ?>

  <form method="post">
      <p>お名前: <input id="my_name" type="text" name="my_name" value=""} ?></p>
      <p>性別: <input type="radio" name="gender" value="man" <?php if ($gender === 'man') { print 'checked'; } ?>>男
      <input type="radio" name="gender" value="woman" <?php if ($gender === 'woman') { print 'checked'; } ?>>女</p>
      <p><input type="checkbox" name="mail" value="OK" <?php if ($mail === 'OK') { print 'checked'; } ?>>お知らせメールを受け取る</p>
      <input type="submit" name="submit" value="送信">
  </form>
</body>
</html>
