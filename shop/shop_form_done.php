<?php
session_start();
session_regenerate_id(true);


    if(isset($_SESSION["member_login"]) === false) {
    print "ログインしてく下さい。<br><br>";
    print "<a href='../member_login/member_login.html'>ログイン画面へ<br><br></a>";
    print "<a href='shop_list.php'>TOP画面へ</a>";
    exit();
}
    if(isset($_SESSION["member_login"]) === true) {
    print "ようこそ";
    print $_SESSION["member_name"];
    print "様　";
    print "<a href='../member_login/member_logout.php'>ログアウト</a>";
    print "<br><br>";
    }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>商品購入決定画面</title>
<link rel="stylesheet" href="../style.css">
</head>
    
<body>
    
<?php
 try {   
        
require_once("../common/common.php");

$post = sanitize($_POST);
    
$oname = $post["name"];
$email = $post["email"];
$address = $post["address"];
$tel = $post["tel"];
$cart = $_SESSION["cart"];
$kazu = $_SESSION["kazu"];
$max = count($cart);
    
print $oname."様<br>";
print "ご注文ありがとうございました。<br>";
print $email."にメールを送りましたのでご確認下さい。<br>";
print "商品は入金を確認次第、下記の住所に発送させて頂きます。<br>";
print $address."<br>";
print $tel."<br>";
        
$honbun = "";
$honbun .= $oname."様\n\nこの度はご注文ありがとうございました\n";
$honbun .= "\n";
$honbun .= "ご注文商品\n";
$honbun .= "-------------\n";
        
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
        
for($i = 0; $i < $max; $i++) {
    
$sql = "SELECT name, price FROM mst_product WHERE code=?";
$stmt = $dbh -> prepare($sql);
$data[0] = $cart[$i];
$stmt -> execute($data);
    
$rec = $stmt -> fetch(PDO::FETCH_ASSOC);
    
$name = $rec["name"];
$price = $rec["price"];
$kakaku[] = $price;
$suryo = $kazu[$i];
$shokei = $price * $suryo;
    
$honbun .= $name."";
$honbun .= $price."円×";
$honbun .= $suryo."個=";
$honbun .= $shokei."円\n";
}

$sql = "LOCK TABLES dat_sales_product WRITE";
$stmt = $dbh -> prepare($sql);
$stmt -> execute();
             
for($i = 0; $i < $max; $i++) {
$sql = "INSERT INTO dat_sales_product(sales_member_code, code_product, price, quantity, time) VALUES(?,?,?,?,now())";
$stmt = $dbh -> prepare($sql);
$data = array();
$data[] = $_SESSION["member_code"];
$data[] = $cart[$i];
$data[] = $kakaku[$i];
$data[] = $kazu[$i];
$stmt -> execute($data);
}
        
$sql = "UNLOCK TABLES";        
$stmt = $dbh -> prepare($sql);
$stmt -> execute();
        
$dbh = null;
        
$honbun .= "送料は無料です。\n";
$honbun .= "-------------\n";
$honbun .= "\n";
$honbun .= "代金は以下の口座にお振込み下さい。\n";                                         
$honbun .= "A銀行 B支店 普通口座 1234567\n";
$honbun .= "入金が確認取れ次第、商品を発送させていただきます。\n";
$honbun .= "\n";
$honbun .= "◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆\n";
$honbun .= "～ショップ～\n";
$honbun .= "\n";
$honbun .= "町屋\n";
$honbun .= "電話　090-0000-0000\n";
$honbun .= "メール　toxumuharudev@toxumuharudev.work\n";
$honbun .= "◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆\n";
print "<br>";
print nl2br($honbun);
        
$title = "ご注文ありがとうございました。";
$header = "From:toxumuharudev@toxumuharudev.work";
$honbun = html_entity_decode($honbun, ENT_QUOTES, "UTF-8");
$from = "toxumuharudev@toxumuharudev.work";
mb_language("Japanese");
mb_internal_encoding("UTF-8");
mb_send_mail($email, $title, $honbun, $header, "-f $from");
        
$title = "お客様よりご注文が入りました。";
$header = "From:".$email;
$honbun = html_entity_decode($honbun, ENT_QUOTES, "UTF-8");
mb_language("Japanese");
mb_internal_encoding("UTF-8");
mb_send_mail("toxumuharudev@toxumuharudev.work", $title, $honbun, $header,  "-f $from");
}

catch(Exception $e) {
    print $e->getMessage();
    print "<br>";
    print "只今障害により大変ご迷惑をおかけしております。";
    exit();
}
    
?>
    
<br>
    <?php $_SESSION["cart"] = array();?>
    <?php $_SESSION["kazu"] = array();?>
    <a href="shop_list.php">商品画面へ</a>
<br><br>

</body>
</html>
