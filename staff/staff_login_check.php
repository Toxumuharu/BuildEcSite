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
    
if ($link) { // success to login database
    $db_selected = mysqli_select_db($link, "shop");
    $sql = "SELECT name FROM mst_staff WHERE code=$code AND password=$pass";
    $result = mysqli_query($link, $sql);
    echo "{\$result: $result}";

    if(empty($result["name"]) === true) {
        print "入力が間違っています。<br><br>";
        print "<a href='staff_login.html'>戻る</a>";
        exit();
    } else {
        session_start();
        $_SESSION["login"] = 1;
        $_SESSION["name"] = $result["name"];
        $_SESSION["code"] = $code;
        header("Location:staff_login_top.php");
        exit();
    }

}else{
    echo nl2br("Fail to login database");
}
mysqli_close($link);

// $sql = "SELECT name FROM mst_staff WHERE code=$code AND password=$pass";
// $stmt = $dbh -> prepare($sql);
// $data[] = $code;
// $data[] = $pass;
// $stmt -> execute($data);
    
// $dbh = null;
    
// $rec = $stmt -> fetch(PDO::FETCH_ASSOC);
    
// if(empty($rec["name"]) === true) {
//     print "入力が間違っています。<br><br>";
//     print "<a href='staff_login.html'>戻る</a>";
//     exit();
// } else {
//     session_start();
//     $_SESSION["login"] = 1;
//     $_SESSION["name"] = $rec["name"];
//     $_SESSION["code"] = $code;
//     header("Location:staff_login_top.php");
//     exit();
// }
}
catch(Exception $e) {
    print "只今障害が発生しております。<br><br>";
    print "<a href='staff_login.html'>戻る</a>";
}
?>