<?php
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

    $link = mysqli_connect($_["datasource"], $_["userid"], $_["password"], $_["database"]);

    echo nl2br("リンク集\n");
    echo nl2br("<a href='staff_login.html'>- サインイン画面</a>\n");

    if ($link) {
        $db_selected = mysqli_select_db($link, $_["database"]);
    
        $sql = "INSERT INTO access (AccessTime) VALUES ('2018-04-17 11:59')";
        $result_flag = mysqli_query($link, $sql);
    
        if ($result_flag) {
            // echo nl2br("成功しました\n");

            $result = mysqli_query($link, 'SELECT count(*) as count from Access');  
            $row = mysqli_fetch_assoc($result);
            // print("アクセス数: ". $row["count"]); 
            // echo nl2br("\n");
        } else {
            die('INSERTクエリーが失敗しました。'.mysqli_error($link));
        }
        // // add test
        // $sql = "SELECT * FROM access";
        // $result = mysqli_query($link, $sql);
        // // 結果を出力
        // $count = 1;
        // while( $row_data = mysqli_fetch_array( $result) ) {
        //     echo nl2br("in while $count: ");
        //     var_dump($row_data);
        //     echo nl2br("\n");
        //     $count ++;
        // }
        mysqli_close($link);
    }
    else {
        $link = mysqli_connect($_["datasource"], $_["userid"], $_["password"], $_["database"]);
        print(mysqli_error($link));
    }
?>