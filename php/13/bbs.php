<?php
function h($s) {
  return htmlspecialchars($s, ENT_QUOTES, "UTF-8");
}
date_default_timezone_set('Asia/Tokyo');
$err_msg1 = "";
$err_msg2 = "";
$err_msg3 = "";
$err_msg4 = "";
$name = (isset($_POST["comment"]) === true) ?$_POST["name"]: "";
$comment = (isset($_POST["comment"]) === true) ? trim($_POST["comment"]): "";

if (  isset($_POST["submit"] ) ===  true ) {
    if ( $name   === "" ) $err_msg1 = "名前を入力してください"; 
 
    if ( $comment  === "" )  $err_msg2 = "ひとことを入力してください";
 
    if( $err_msg1 === "" && $err_msg2 ==="" ){
        $fp = fopen( "review.txt" ,"a" );
        fwrite( $fp ,  $name."\t".$comment."\n");
    }
 
}

$fp = fopen("review.txt","r");
 
$dataArr = array();
while($res = fgets($fp)){
    $tmp = explode("\t",$res);
    $arr = array(
        "name"=>$tmp[0],
        "comment"=>$tmp[1]
    );
    $dataArr[]= $arr;
} 
?>

<!DOCTYPE html>
<html lamg="ja">
<head>
  <meta charset="UTF-8">
  <title>bbs</title>
</head>
<body>
  <h1>ひとこと掲示板</h1>
  <ul>
  <li><?php echo $err_msg1; ?></li>
  <li><?php echo $err_msg2; ?></li>
  </ul>
  <form method="post">
    名前：<input type="text" name="name" value="<?php echo $name; ?>">
    ひとこと：<input type="text" name="comment" value="<?php echo $comment; ?>">

    <input type="submit" name="submit" value="送信">
  </form>
  <ul>
    <?php foreach ($dataArr as $data): ?>
        <li>
          <span><?php echo $data["name"]; ?></span>：<span><?php echo $data["comment"]; ?></span>
        </li>
    <?php endforeach; ?>
　</ul>
</body>
</html>
