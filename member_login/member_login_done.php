<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ログイン実行</title>
<link rel="stylesheet" href="../style.css">
</head>
    
<body>

<?php
    try{

require_once("../common/common.php");

$post = sanitize($_POST);

$email = $post["email"];
$pass = $post["pass"];
        
$pass = md5($pass);
        
// azure database login
$azure_mysql_connstr = $_SERVER["MYSQLCONNSTR_localdb"];
$azure_mysql_connstr_match = preg_match(
    "/".
    "Database=(?<database>.+);".
    "Data Source=(?<datasource>.+);".
    "User Id=(?<userid>.+);".
    "Password=(?<password>.+)".
    "/u",
    $azure_mysql_connstr,
    $_);
    
$dsn = "mysql:host={$_["datasource"]};dbname=shop;charset=utf8";
$user = $_["userid"];
$password = $_["password"];

$dbh = new PDO($dsn, $user, $password);
$dbh -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
$sql = "SELECT code, name FROM member WHERE email=? AND password=?";
$stmt = $dbh -> prepare($sql);
$data[] = $email;
$data[] = $pass;
$stmt -> execute($data);
        
$dbh = null;
        
$rec = $stmt -> fetch(PDO::FETCH_ASSOC);
        
if(empty($rec["name"]) === true) {
    print "ログイン情報が間違っています。<br>";
    print "<a href='member_login.php'>戻る</a>";
    exit();
} 
session_start();
$_SESSION["member_login"] = 1;
$_SESSION["member_name"] = $rec["name"];
$_SESSION["member_code"] = $rec["code"];
print "ログイン成功<br><br>";
print "<a href='../shop/shop_list.php'>トップへ戻る</a>";
        
}
catch(Exception $e) {
   print "只今障害が発生しております。";
   print "a href='member_login.php'>ログインページへ戻る</a>";
   exit();
}
?>
    <br><br>

</body>
</html>