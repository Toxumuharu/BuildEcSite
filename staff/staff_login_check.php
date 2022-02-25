<?php

function start_function($name){
   echo nl2br("$name {\n");
}

function end_function($name){
    echo nl2br("} // $name \n\n");
}

function show_result($result){
    start_function(__FUNCTION__);
    print_r(mysqli_fetch_assoc($result));
    echo nl2br("\n");
    end_function(__FUNCTION__); 
}

function show_sql($sql){
    start_function(__FUNCTION__);
    echo nl2br("{$sql}\n");
    end_function(__FUNCTION__); 
}

try {

require_once("../common/common.php");

$post = sanitize($_POST);
$code = $post["code"];
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

$sql = "SELECT name FROM mst_staff WHERE code=? AND password=?";
$stmt = $dbh -> prepare($sql);
$data[] = $code;
$data[] = $pass;
$stmt -> execute($data);

$dbh = null;
$rec = $stmt -> fetch(PDO::FETCH_ASSOC);

if(empty($rec["name"]) === true) {
    print "入力が間違っています。<br><br>";
    print "<a href='staff_login.html'>戻る</a>";
    exit();
} else {
    session_start();
    $_SESSION["login"] = 1;
    $_SESSION["name"] = $rec["name"];
    $_SESSION["code"] = $code;
    header("Location:staff_login_top.php");
    exit();
}

}catch(Exception $e) {
    print ($e);
    print "只今障害が発生しております。<br><br>";
    print "<a href='staff_login.html'>戻る</a>";
} 
?>