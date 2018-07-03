<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <title>Cookie</title>
  </head>
  <body>
    <?php
    $date = date("Y年m月d日 H時i分s秒");
    // cookieが設定されていなければ(初回アクセス)、cookieを設定する
    if (!isset($_COOKIE['visit_count'])) {
      // cookieを設定
      setcookie('visit_count', 1);
      setcookie('visit_history', $date);
      print("初めてのアクセスです<br>");
      print($date."（現在日時)<br>");
    }
    // cookieがすでに設定されていれば(2回目以降のアクセス)、cookieで設定した数値を加算する
    else {
      $count = $_COOKIE['visit_count'] + 1;
      $visit_history = $_COOKIE['visit_history'];
      setcookie('visit_count', $count);
      setcookie('visit_history', $date);
      print("合計".$count."回目のアクセスです<br>");
      print($date."（現在日時)<br>");
      print($visit_history."（前回のアクセス日時）<br>");
    }
    if (isset($_POST['delete'])) {
        setcookie('visit_count', '', time()-3600);
        setcookie('visit_history', '', time()-3600);
    }
    ?>
    <form method="post" action="challenge_cookie.php">
      <input type="submit" name="delete" value="履歴削除">
    </form>
  </body>
</html>
