<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>会員情報入力チェック</title>
<link rel="stylesheet" href="../style.css">
</head>
    
<body>

<?php

require_once("../common/common.php");

$post = sanitize($_POST);
    
$name = $post["name"];
$address = $post["address"];
$tel = $post["tel"];
$email = $post["email"];
$pass = $post["pass"];
$pass2 = $post["pass2"];
$okflag = true;
    
if(empty($name) === true) {
    print "お名前を入力してください。<br>";
    $okflag = false;
}
if(empty($email) === true) {
    print "emailを入力してください。<br>";
    $okflag = false;
}
if(preg_match("/\A[\w\-\.]+\@[\w\-\.]+\.([a-z]+)\z/", $email) === 0) {
    print "正しいemailを入力してください。<br>";
    $okflag = false;
}
if(empty($address) === true) {
    print "住所を入力してください。<br>";
    $okflag = false;
}
if(empty($tel) === true) {
    print "電話番号を入力してください。<br>";
    $okflag = false;
}
if(preg_match("/\A\d{2,5}-?\d{2,5}-?\d{4,5}\z/", $tel) === 0) {
    print "正しい電話番号を入力してください。<br>";
    $okflag = false;
}
if(empty($pass) === true) {
    print "パスワードを入力してください。<br>";
    $okflag = false;
}
if($pass != $pass2) {
    print "パスワードが異なります<br>";
    $okflag = false;
}
if($okflag === false) {
    print "<form><br>";
    print "<input type='button' onclick='history.back()' value='戻る'>";
} else {
    print "下記内容で登録しますか？<br><br>";
    print $name."<br><br>";
    print $email."<br><br>";
    print $address."<br><br>";
    print $tel."<br><br>";
    print "<form action='menber_login_db_done.php' method='post'>";
    print "<input type='hidden' name='name' value='".$name."'>";
    print "<input type='hidden' name='email' value='".$email."'>";
    print "<input type='hidden' name='address' value='".$address."'>";
    print "<input type='hidden' name='tel' value='".$tel."'>";
    print "<input type='hidden' name='pass' value='".$pass."'>";
    print "<input type='button' onclick='history.back()' value='戻る'>";
    print "<input type='submit' value='登録'>";
}
?>
<br><br>
    
</body>
</html>