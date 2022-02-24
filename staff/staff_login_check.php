<?php
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
    
// $dsn = "mysql:host=$_["datasource"];dbname=shop;charset=utf8";
// $user = $_["userid"];
// $password = "";
// $dbh = new PDO($dsn, $user, $_["password"]);
// $dbh -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
if ($link) { // success to login database
    $db_selected = mysqli_select_db($link, "shop");
    $sql = "SELECT code FROM mst_staff WHERE name = '$code' AND password = '$pass'";
    echo nl2br("{$sql}");
    $result = mysqli_query($link, $sql);
    
    // if ($result)
    // {
    // // Fetch one and one row
    // while ($row=mysqli_fetch_row($result))
    //     {
    //     printf ("%s (%s)\n",$row[0],$row[1]);
    //     }
    // }

    echo nl2br("result:");
    $result = mysqli_fetch_assoc($result);
    print_r($result);
    echo nl2br("\n");



        if(empty($result["code"]) === true) {
            print "入力が間違っています。<br><br>";
            print "<a href='staff_login.html'>戻る</a>";
            exit();
        } else {
            echo nl2br("session_start()\n");
            session_start();
            $_SESSION["login"] = 1;
            $_SESSION["name"] = $result["name"];
            $_SESSION["code"] = $code;
            header("Location:staff_login_top.php");
            exit();
        }
    mysqli_close($link);
}else{
    echo nl2br("Fail to login database");
    $link = mysqli_connect($_["datasource"], $_["userid"], $_["password"], "shop");
    mysqli_close($link);
}
}
catch(Exception $e) {
    print ($e);
    print "只今障害が発生しております。<br><br>";
    print "<a href='staff_login.html'>戻る</a>";
} 
?>