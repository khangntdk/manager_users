<?php
// ... (các dòng mã trước đó nếu có trong file select.php)

// truy vấn 1 dòng dữ liệu

$sql = "SELECT * FROM hocsinh WHERE id = ?";
// $id = 10;

try{
    $statement = $conn -> prepare($sql);

    // $arr = [$id];
    $statement -> execute([9]);

    $data = $statement -> fetch(PDO::FETCH_ASSOC);

    echo '<pre>';
    print_r($data);
    echo '</pre>';

}catch(Exception $exp){
    echo $exp -> getMessage().'<br>';
    echo 'File: '. $exp -> getFile().'<br>';
    echo 'Line: '. $exp -> getLine();
}