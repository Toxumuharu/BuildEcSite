<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>会員登録完了</title>
<link rel="stylesheet" href="../style.css">
</head>
    
<body>

<?php
    try{

require_once("../common/common.php");

$post = sanitize($_POST);

$name = $post["name"];
$address = $post["address"];
$tel = $post["tel"];
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
        
$sql = "SELECT email FROM menber WHERE1";
$stmt = $dbh -> prepare($sql);
$stmt -> execute();
        
while(true) {
    $rec = $stmt -> fetch(PDO::FETCH_ASSOC);
    if(empty($rec) === true) {
        break;
    }
    $mail[] = $rec["email"];
}

if(empty($mail) === true) {
    $mail[] = "a";
}
        
if(in_array($email, $mail) === true) {
    print "すでに使われているmailアドレスです。<br><br>";
    print "<a href='menber_login_db.php'>トップへ戻る</a>";
    $dbh = null;
} else {   
$sql = "INSERT INTO menber(name, email, address, tel, password) VALUES(?,?,?,?,?)";
$stmt = $dbh -> prepare($sql);
$data[] = $name;
$data[] = $email;
$data[] = $address;
$data[] = $tel;
$data[] = $pass;
$stmt -> execute($data);
        
$dbh = null;
        
 
print "登録完了しました。<br><br>";
print "<a href='../shop/shop_list.php'>トップへ戻る</a>";
}
}
catch(Exception $e) {
   print "只今障害が発生しております。";
   print "a href='menber_login.php'>ログインページへ戻る</a>";
   exit();
}
?>
<br><br>
    
</body>
</html>