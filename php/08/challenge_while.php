<?php
$sum = 0;
while ($i <= 100) {
  $i++;
  if ($i % 3 == 0) {
    $sum += $i;
}
}
print '合計：' . $sum;
?>
