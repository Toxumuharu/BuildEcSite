<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>商品削除</title>
    <link rel="stylesheet" href="../style.css">
</head>

<body>
    <?php

    session_start();
    session_regenerate_id(true);
    if (isset($_SESSION["login"]) === false) {
        print "ログインしていません。<br><br>";
        print "<a href='staff_login.php'>ログイン画面へ</a>";
        exit();
    } else {
        print $_SESSION["name"] . "さんログイン中";
        print "<p class='text-primary'>.text-primary</p>";
        print "<div class='p-3 mb-2 bg-primary text-white'>.bg-primary</div>";

        print "<br><br>";
    }
    ?>

    <?php
    try {

        $code = $_GET["code"];

        // azure database login
        $azure_mysql_connstr = $_SERVER["MYSQLCONNSTR_localdb"];
        $azure_mysql_connstr_match = preg_match(
            "/" .
                "Database=(?<database>.+);" .
                "Data Source=(?<datasource>.+);" .
                "User Id=(?<userid>.+);" .
                "Password=(?<password>.+)" .
                "/u",
            $azure_mysql_connstr,
            $_
        );

        $dsn = "mysql:host={$_["datasource"]};dbname=shop;charset=utf8";
        $user = $_["userid"];
        $password = $_["password"];

        $dbh = new PDO($dsn, $user, $password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT code, name, price, gazou FROM mst_product WHERE code=?";
        $stmt = $dbh->prepare($sql);
        $data[] = $code;
        $stmt->execute($data);

        $dbh = null;

        $rec = $stmt->fetch(PDO::FETCH_ASSOC);

        $gazou = $rec["gazou"];

        if (empty($gazou) === true) {
            $disp_gazou = "";
        } else {
            $disp_gazou = "<img src='./gazou/" . $gazou . "'>";
        }
    } catch (Exception $e) {
        print "只今障害が発生しております。<br><br>";
        print "<a href='../staff/staff_login.php'>ログイン画面へ</a>";
    }
    ?>

    商品詳細<br><br>
    商品コード<br>
    <?php print $rec["code"]; ?>
    <br><br>
    商品名<br>
    <?php print $rec["name"]; ?>
    <br><br>
    価格<br>
    <?php print $rec["price"]; ?>
    円<br>
    <br>
    画像<br>
    <?php print $disp_gazou; ?>
    <br><br>
    上記情報を削除しますか？<br><br>
    <form action="pro_delete_done.php" method="post">
        <input type="hidden" name="code" value="<?php print $rec['code']; ?>">
        <input type="hidden" name="gazou" value="<?php print $gazou; ?>">
        <input type="button" onclick="history.back()" value="戻る">
        <input type="submit" value="OK">
    </form>

</body>

</html>