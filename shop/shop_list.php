<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>ECサイトTOP</title>
    <!-- <link rel="stylesheet" href="../style.css"> -->
    <?php
    session_start();
    session_regenerate_id(true);

    if (isset($_SESSION["member_login"]) === true) {
        print "<div class='p-3 mb-2 bg-primary text-white'>";
        print "ようこそ";
        print $_SESSION["member_name"];
        print "様";
        print "</div>";
        print "<a href='../member_login/member_logout.php'>ログアウト</a>";
        print "<br><br>";
    }

    ?>
</head>


<body>

    <?php
    try {

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

        $sql = "SELECT code,name,price,gazou,explanation FROM mst_product WHERE1";
        $stmt = $dbh->prepare($sql);
        $stmt->execute();

        $dbh = null;

        print "販売商品一覧";
        print " <a href='shop_cartlook.php'>カートを見る</a>";
        print "<br><br>";

        while (true) {
            $rec = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($rec === false) {
                break;
            }
            $code = $rec["code"];
            print "<a href='shop_product.php?code=" . $code . "'>";
            if (empty($rec["gazou"]) === true) {
                $gazou = "";
            } else {
                $gazou = "<img src='../product/gazou/" . $rec['gazou'] . "'>";
            }
            print $gazou;
            print "<br>";
            print "商品名:" . $rec["name"];
            print "<br>";
            print "価格:" . $rec["price"] . "円";
            print "<br>";
            print "詳細:" . $rec["explanation"];
            print "</a>";
            print "<br><br>";
        }
        print "<br>";
    } catch (Exception $e) {
        print "只今障害が発生しております。<br><br>";
        print "<a href='../staff/staff_login.php'>ログイン画面へ</a>";
    }
    ?>

    <h3>カテゴリー</h3>
    <ul>
        <li><a href="shop_list_eart.php">食品</a></li>
        <li><a href="shop_list_kaden.php">家電</a></li>
        <li><a href="shop_list_book.php">書籍</a></li>
        <li><a href="shop_list_niti.php">日用品</a></li>
        <li><a href="shop_list_sonota.php">その他</a></li>
    </ul>

</body>

</html>