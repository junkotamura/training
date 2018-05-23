<?php
setlocale(LC_ALL,'ja_JP.UTF-8');
$csv = array();
$file = "tokyo.csv";
$data = file_get_contents($file);
$data = mb_convert_encoding($data, 'UTF-8', 'sjis-win'); 
$temp = tmpfile();
$csv = array();
fwrite($temp, $data);
rewind($temp);
while (($data = fgetcsv ($temp, 0, ",")) !== FALSE ) {
$data = implode(",", $data);
$csv[] = htmlentities($data); 
}
$result = array();
$table = '';
for ($i = 0; $i < 20; $i++) {
$result[$i] = explode(",", $csv[$i]);
$table .= '<tr>';
$table .= '<td>' .$result[$i][2]. '</td>';
for ($k = 6; $k <= 8; $k++) {
$village[$i][$k] = $result[$i][$k];
$table .= '<td>' .$village[$i][$k]. '</td>';
}
$table .= '</tr>';
}
fclose($temp);
?>
<!DOCTYPE html
<html lang="ja">
<head>
  <title>課題2</title>
<style type="text/css">
.table {
border-collapse: collapse;
}
.table th {
background-color: #cccccc;
}
.table td {
padding: 4px;
}
.t-head td {
font-weight: bold;
}
</style>
</head>
<body>
<p>以下にファイルから読み込んだ住所データを表示</p>
<p>住所データ</p>
<table class="table" border=1>
    <tr class="t-head">
      <td>郵便番号</td>
      <td>都道府県</td>
      <td>市町村</td>
      <td>町域</td>
    </tr>
    <?php echo $table ?>
  </table>
</body>
</html>
