<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>ifの使用例</title>
</head>
<body>
 <?php
 $rand1 = mt_rand(0, 2);
 $rand2 = mt_rand(0, 2);
 ?>
 <p>rand1：<?php print $rand1; ?></p>
 <p>rand2：<?php print $rand2; ?></p>
 <?php if ($rand1 > $rand2) { ?>
 <p>大きいのはrand1</p>
 <?php } else if ($rand1 < $rand2) { ?>
 <p>'大きいのはrand2で</p>
 <?php  } else { ?>
 <p>同じ値です</p>
 <?php } ?>
</body>
</htmli>
