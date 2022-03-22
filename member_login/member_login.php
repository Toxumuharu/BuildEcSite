<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ログイン入力</title>
<link rel="stylesheet" href="../style.css">
</head>
    
<body>

会員情報を入力してください。
<br><br>
mailアドレス<br>
<form action="member_login_check.php" method="post">
<input type="text" name="email">
<br>
パスワード<br>
<input type="password" name="pass">
<br>
パスワード再入力<br>
<input type="password" name="pass2">
<br><br>
<input type="button" onclick="history.back()" value="戻る">
<input type="submit" value="OK">
<br><br>
会員情報が未登録の方はこちらから登録をお願いします。<br>
<a href="./member_login_db.php">会員登録画面へ</a>
    
</form>
<br><br>

</body>
</html>