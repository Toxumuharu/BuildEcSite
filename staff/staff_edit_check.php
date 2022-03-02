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
<title>スタッフ修正チェック</title>
<link rel="stylesheet" href="style.css">
</head>
    
<body>
    
<?php
    
require_once("../common/common.php");
    
$post = sanitize($_POST);
$code = $post["code"];
$name = $post["name"];
$pass = $post["pass"];
$pass2 = $post["pass2"];
    
print "スタッフコード<br>";
print $code;
print " の情報を修正します。";
print "<br><br>";
    
if(empty($name) === true) {
    print "名前が入力されていません。<br><br>";
} else {
    print "スタッフ名:";
    print $name;
    print "<br><br>";
}
    
if(empty($pass) === true) {
    print "パスワードが入力されていません。<br><br>";
}
    
if($pass != $pass2) {
    print "パスワードが異なります。<br><br>";
}
    
if(empty($name) or empty($pass) or $pass != $pass2) {
    print "<form>";
    print "<input type='button' onclick='history.back()' value='戻る'>";
    print "</form>";
} else {
    $pass = md5($pass);
    print "上記の通り修正しますか？<br><br>";
    print "<form action='staff_edit_done.php' method='post'>";
    print "<input type='hidden' name='name' value='".$name."'>";
    print "<input type='hidden' name='pass' value='".$pass."'>";
    print "<input type='hidden' name='code' value='".$code."'>";
    print "<input type='button' onclick='history.back()' value='戻る'>";
    print "<input type='submit' value='OK'>";
    print "</form>";
}
?>
</body>
</html>