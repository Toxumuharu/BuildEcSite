<?php

session_start();
session_regenerate_id(true);
if(isset($_SESSION["login"]) === false) {
    print "ログインしていません。<br><br>";
    print "<a href='staff_login.php'>ログイン画面へ</a>";
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
<title>スタッフ一覧</title>
<link rel="stylesheet" href="../style.css">
</head>
    
<body>
    
<?php
try{
    
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
    
$sql = "SELECT code,name FROM mst_staff WHERE1";
$stmt = $dbh -> prepare($sql);
$stmt -> execute();
    
$dbh = null;
    
print "スタッフ一覧<br><br>";
print "<form action='staff_branch.php' method='post'>";
    
while(true) {
    $rec = $stmt -> fetch(PDO::FETCH_ASSOC);
    if($rec === false) {
        break;
    }
    print "<input type='radio' name='code' value='".$rec['code']."'>";
    print $rec["name"];
    print "<br>";
}
print "<br>";
print "<input type='submit' name='disp' value='詳細'>";
print "<input type='submit' name='add' value='追加'>";
print "<input type='submit' name='edit' value='修正'>";
print "<input type='submit' name='delete' value='削除'>";
}
catch(Exception $e) {
    print "只今障害が発生しております。<br><br>";
    print "<a href='../staff/staff_login.php'>ログイン画面へ</a>";
}
?>
<br><br>
<a href="../staff/staff_login_top.php">管理画面TOPへ</a>
    
</body>
</html>