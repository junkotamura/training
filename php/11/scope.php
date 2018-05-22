<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>スコープ</title>
  </head>
  <body>
  <?php
  function test_scope() {
  print $_SERVER['REQUEST_METHOD']; // スーパーグローバル変数を参照
  }
  test_scope();
  ?>
  </body>
</html>
