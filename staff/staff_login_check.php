<?php

function start_function(){
    $dbg = debug_backtrace();
    $name = $dbg["function"];
    var_dump($dbg);
    echo nl2br("start: $name \n");
}

function end_function($name){
    echo nl2br("end: $name \n");
}

function show_result($result){
    start_function();
    print_r(mysqli_fetch_assoc($result));
    echo nl2br("\n");
    end_function(__FUNCTION__); 
}

function show_sql($sql){
  echo nl2br("{$sql}\n");
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
$link = mysqli_connect($_["datasource"], $_["userid"], $_["password"], "shop");
    
if ($link) { // success to login database
    $db_selected = mysqli_select_db($link, "shop");
    $sql = "SELECT name FROM mst_staff WHERE code = '$code' AND password = '$pass'";
    //$sql = "SELECT code FROM mst_staff WHERE name = '$code' AND password = '$pass'"; // if name and code

    show_sql($sql);
    $result = mysqli_query($link, $sql);
    show_result($result);
    if(empty($result["name"]) === true) {
        echo nl2br("true\n");
    }else{
        echo nl2br("false\n");
    }

    if(empty($result["name"]) === true) {
    // if(empty($result["code"]) === true) { // if name and code
        print "入力が間違っています。<br>";
        print "<a href='staff_login.html'>戻る</a>";
        exit();
    } else {
        echo nl2br("session_start();");
        session_start();
        $_SESSION["login"] = 1;
        $_SESSION["name"] = $result["name"];
        $_SESSION["code"] = $code;
        // $_SESSION["name"] = $code; // if name and code
        // $_SESSION["code"] = $result["code"]; // if name and code
        header("Location:staff_login_top.php");
        exit();
    }
    mysqli_close($link);
}else{
    echo nl2br("Fail to login database");
    $link = mysqli_connect($_["datasource"], $_["userid"], $_["password"], "shop");
    mysqli_close($link);
}

}catch(Exception $e) {
    print ($e);
    print "只今障害が発生しております。<br><br>";
    print "<a href='staff_login.html'>戻る</a>";
} 
?>