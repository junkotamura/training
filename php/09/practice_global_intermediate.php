<?php
  if ($_SERVER['REQUEST_METHOD'] === 'POST') 
  if (isset($_POST['janken'])) {
    $janken = array('グー', 'チョキ', 'パー');
    $player = htmlspecialchars($_POST['janken'], ENT_QUOTES, 'UTF-8');
    $com = $janken[array_rand($janken)];
    $result = false;
    if ($player === $com) {
        $result = 'draw';
    } else if ( 
        $player === 'グー' && $com === 'チョキ' ||
        $player === 'チョキ' && $com === 'パー' ||
        $player === 'パー' && $com === 'グー' 
      ) {
        $result = 'Win!!';
    } else {
        $result = 'lose...';
    }  
  }
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>課題3</title>
</head>
<body>
  <h1>じゃんけん勝負</h1>
  <form method="post">
    <p>自分：<?php print $player; ?></p>
    <p>相手：<?php print $com; ?></p>
    <p>結果：<?php print $result; ?></p>

    <p><input type="radio" name="janken" value="グー">グー
    <input type="radio" name="janken" value="チョキ">チョキ
    <input type="radio" name="janken" value="パー">パー</p>

    <input type="submit" name="go" value="勝負!!">
  </form>
</body>
</html>
