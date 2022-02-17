<?php

//session_start();
//session_regenerate_id(true);
//if(isset($_SESSION["login"]) === false) {
//    print "ログインしていません。<br><br>";
//    print "<a href='staff_login.html'>ログイン画面へ</a>";
//    exit();
//} else {
//    print $_SESSION["name"]."さんログイン中";
//    print "<br><br>";
//}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>スタッフ追加実効</title>
<link rel="stylesheet" href="../style.css">
</head>
    
<body>
    
<?php
try{
    // check variables
    require_once("../common/common.php");
    $post = sanitize($_POST);
    $name = $post["name"];
    $pass = $post["pass"];

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
        $sql = "INSERT INTO mst_staff(name, password) VALUES('$name','$pass')";
        $result_flag = mysqli_query($link, $sql);

        // // show added value
        // $sql = "SELECT * FROM mst_staff";
        // $result = mysqli_query($link, $sql);
        //     // 結果を出力
        //     echo nl2br("result:\n");
        //     $count = 1;
        //     while($row_data = mysqli_fetch_array($result)) {
        //         echo nl2br("in while $count: ");
        //         var_dump($row_data);
        //         echo nl2br("\n");
        //         $count ++;
        //     } 
    }else{
        echo nl2br("Fail to login database");
    }
    mysqli_close($link);

    // $dsn = "mysql:host=localhost;dbname=shop;charset=utf8";
    // $user = "root";
    // $password = "";
    // $dbh = new PDO($dsn, $user, $password);
    // $dbh -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
    // $sql = "INSERT INTO mst_staff(name, password) VALUES(?,?)";
    // $stmt = $dbh -> prepare($sql);
    // $data[] = $name;
    // $data[] = $pass;
    // $stmt -> execute($data);
        
    // $dbh = null;
}
catch(Exception $e) {
    print "只今障害が発生しております。<br><br>";
    print "<a href='../staff_login/staff_login.html'>ログイン画面へ</a>";
}
?>
    
スタッフを追加しました。<br><br>
<a href="staff_list.php">スタッフ一覧へ</a>

</body>
</html>