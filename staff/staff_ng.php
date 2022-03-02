<?php

session_start();
session_regenerate_id(true);
if(isset($_SESSION["login"]) === false) {
    print "ログインしていません。<br><br>";
    print "<a href='staff_login.html'>ログイン画面へ</a>";
    exit();
} else {
    print $_SESSION["name"]."さんログイン中";
    print "<br><br>";
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>スタッフ選択NG</title>
<link rel="stylesheet" href="../style.css">
</head>
    
<body>
    
スタッフが選択されていません。<br><br>
<a href="staff_list.php">スタッフ一覧に戻る</a>

</body>
</html>