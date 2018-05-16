<?php
$apple = 100;
$grape = 150;
 
/* 以下に「648」と合計金額が表示されるよう課題の処理を追加しましょう */
$sum = ($apple * 3) + ($grape * 2);
$tax = $sum * 108/ 100;
print '合計：' . $tax;
?>
