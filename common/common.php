<?php

function sanitize($before) {
    foreach($before as $key => $value) {
        $after[$key] = htmlspecialchars($value, ENT_QUOTES,"UTF-8");
    }
    return $after;
}

function pulldown_cate() {
    print "<select name='cate'>";
    print "<option value='食品'>食品</option>";
    print "<option value='家電'>家電</option>";
    print "<option value='書籍'>書籍</option>";
    print "<option value='日用品'>日用品</option>";
    print "<option value='その他'>その他</option>";
    print "</select>";
}

// function resize_image($original_image) {
//     list($width, $hight) = getimagesize('test.jpg'); // 元の画像名を指定してサイズを取得
//     $baseImage = imagecreatefromjpeg('test.jpg'); // 元の画像から新しい画像を作る準備
//     $image = imagecreatetruecolor(100, 100); // サイズを指定して新しい画像のキャンバスを作成
    
//     // 画像のコピーと伸縮
//     imagecopyresampled($image, $baseImage, 0, 0, 0, 0, 100, 100, $width, $hight);
    
//     // コピーした画像を出力する
//     imagejpeg($image , 'new.jpg');
//     return image;
// }

?>