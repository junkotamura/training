<?php>
$rand = mt_rand(1, 6);
print $rand;
if ($rand === 1 || $rand === 3 || $rand === 5) {
 print '奇数';
} else {
 print '偶数';
}
?>
