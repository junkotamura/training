<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <title>Session</title>
  </head>
  <body>
    <?php
    $date = date("Y年m月d日 H時i分s秒");
    session_start();
    if (!isset($_SESSION['count'])) {
    
        $_SESSION['count'] = 1;
        $_SESSION['history'] = $date;
        print("初めてのアクセスです<br>");
        print($date."（現在日時)<br>");
        
    } else {
        $_SESSION['count']++;
        print("合計".$_SESSION['count']."回目のアクセスです<br>");
        print($date."（現在日時)<br>");
        print($_SESSION['history']."（前回のアクセス日時）<br>");
      }
    
    if (isset($_POST['delete'])) {
        unset($_SESSION['count'],$_SESSION['history']);
    }
    ?>
    <form method="post" action="challenge_cookie.php">
      <input type="submit" name="delete" value="履歴削除">
    </form>
  </body>
</html>
