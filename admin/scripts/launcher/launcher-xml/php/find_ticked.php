<?php


header('Content-Type: text/html; charset=utf-8');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "betrendo";

$connection = mysqli_connect($servername, $username, $password, $dbname);





$xmlfiles = glob('../uploaded/*.xml');
//print_r($xmlfiles);

$shops = [];
$x = 0;

foreach ($xmlfiles as $shop) {
    $cur_shop_found = "";
    $flag_start_output = false;
    $length = strlen($shop);
    for ($i=9; $i < $length; $i++) {
        if ($shop[$i] == "/") {
            $flag_start_output = true;
            $i++;
        }
        if ($shop[$i] == ".") {
            $flag_start_output = false;
            break;
        }
        if ($flag_start_output == true) {
            $cur_shop_found .= $shop[$i];
        }
    }
    array_push($shops,$cur_shop_found);
}


// ----------------------------------------------------------------------------
// Проходим по каждому xml файлу. Переносим данные из xml в sql базу данных
// ----------------------------------------------------------------------------
foreach ($shops as $shop) {
    $sql = "
    UPDATE catalog_".$shop."
    SET catalog_".$shop.".Ticked = '1'
    WHERE 
    (
        (SELECT catalog_".$shop."_accepted.XML_Reference_ID FROM catalog_".$shop."_accepted WHERE catalog_".$shop.".XML_Reference_ID=catalog_".$shop."_accepted.XML_Reference_ID)
    )
    ";
    $data_update = mysqli_query($connection, $sql);
    if (!$data_update) {
        $json_return_data = array(
            "ReturnCode" => -1, // Error
            "ReturnMessage" => ("Ошибка обнаружения Ticked при загрузки новой БД. SQL:" . $sql . " 
            Ошибка : " . $connection -> error)
        );
        exit(json_encode($json_return_data, JSON_UNESCAPED_UNICODE));
    }


    $sql = "
    UPDATE catalog_".$shop."
    SET catalog_".$shop.".Ticked = '1'
    WHERE 
    (
        (SELECT catalog_".$shop."_rejected.XML_Reference_ID FROM catalog_".$shop."_rejected WHERE catalog_".$shop.".XML_Reference_ID=catalog_".$shop."_rejected.XML_Reference_ID)
    )
    ";
    $data_update = mysqli_query($connection, $sql);
    if (!$data_update) {
        $json_return_data = array(
            "ReturnCode" => -1, // Error
            "ReturnMessage" => ("Ошибка обнаружения Ticked при загрузки новой БД. SQL:" . $sql . " 
            Ошибка : " . $connection -> error)
        );
        exit(json_encode($json_return_data, JSON_UNESCAPED_UNICODE));
    }
}




$json_return_data = array(
    "ReturnCode" => 1, // Error
    "ReturnMessage" => ("Success")
);
exit(json_encode($json_return_data, JSON_UNESCAPED_UNICODE));


?>