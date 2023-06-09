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
<title>商品修正実効</title>
<link rel="stylesheet" href="style.css">
</head>
    
<body>
    
<?php
    try{
    
require_once("../common/common.php");
    
$post = sanitize($_POST);
$code = $post["code"];
$name = $post["name"];
$price = $post["price"];
$gazou = $post["gazou"];
$old_gazou = $post["old_gazou"];
$comments = $post["explanation"];
$cate = $post["cate"];
        
if(empty($gazou) && isset($old_gazou) === true) {
    $gazou = $old_gazou;
}
if($old_gazou != "") {
    echo "<br>";
    if($gazou != $old_gazou) {
        echo "<br>";
        unlink("./gazou/".$old_gazou);
        echo "<br>";
    }
    echo "<br>";
}     

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
    
$sql = "UPDATE mst_product SET category=?, name=?, price=?, gazou=?, explanation=? WHERE code=?";
$stmt = $dbh -> prepare($sql);
$data[] = $cate;
$data[] = $name;
$data[] = $price;
$data[] = $gazou;
$data[] = $comments;
$data[] = $code;
$stmt -> execute($data);
    
$dbh = null;
        
  
}
catch(Exception $e) {
    print "只今障害が発生しております。<br><br>";
    print "<a href='../staff/staff_login.php'>ログイン画面へ</a>";
}
?>
    
商品を修正しました。<br><br>
<a href="pro_list.php">商品一覧へ</a>

</body>
</html>