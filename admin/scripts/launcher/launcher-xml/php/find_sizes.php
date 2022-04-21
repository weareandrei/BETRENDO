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
    // Есть ли пробел в названии. Если да - заменяем его на _
    $shop_file_name = $shop;
    $shop = str_replace(" ", "_", $shop);

    // ----------------------------------------------------------------------------
    // Отмечаем лидеров группы
    //    Им будет любой товар с MIN(`ID`) из его группы
    // ----------------------------------------------------------------------------
    if ($shop == "farfetch") {
        $sql = "
        update catalog_".$shop."
        join (select MIN(`ID`) as min_id from catalog_farfetch GROUP BY Group_ID) f
        on f.min_id = catalog_farfetch.`ID`
        set catalog_farfetch.Group_Leader = '1'; 
        ";
    }
    else if ($shop == "brandshop") {
        $sql = "
        update catalog_".$shop."
        join (select MIN(`ID`) as min_id from catalog_".$shop." GROUP BY Picture) f
        on f.min_id = catalog_".$shop.".`ID`
        set catalog_".$shop.".Group_Leader = '1'; 
        ";
    }
    else if ($shop == "aizel") {
        $sql = "
        update catalog_".$shop."
        join (select MIN(`ID`) as min_id from catalog_".$shop." GROUP BY Url) f
        on f.min_id = catalog_".$shop.".`ID`
        set catalog_".$shop.".Group_Leader = '1'; 
        ";
    }
    else if ($shop == "intermoda") {
        $sql = "
        update catalog_".$shop."
        join (select MIN(`ID`) as min_id from catalog_".$shop." GROUP BY Url) f
        on f.min_id = catalog_".$shop.".`ID`
        set catalog_".$shop.".Group_Leader = '1'; 
        ";
    }
    else if ($shop == "the_north_face") {
        $sql = "
        update catalog_".$shop."
        join (select MIN(`ID`) as min_id from catalog_".$shop." GROUP BY Url) f
        on f.min_id = catalog_".$shop.".`ID`
        set catalog_".$shop.".Group_Leader = '1'; 
        ";
    }
    else if ($shop == "superstep") {
        // Тут и все размеры записаны в param каждого товара
        $sql = "
        update catalog_".$shop."
        join (select MIN(`ID`) as min_id from catalog_".$shop.") f
        on f.min_id = catalog_".$shop.".`ID`
        set catalog_".$shop.".Group_Leader = '1'; 
        ";
        
    }
    else if ($shop == "streat_beat") {
        // Тут и все размеры записаны в param каждого товара
        $sql = "
        update catalog_".$shop."
        join (select ID as min_id from catalog_".$shop.") f
        on f.min_id = catalog_".$shop.".`ID`
        set catalog_".$shop.".Group_Leader = '1'; 
        ";
    }
    else if ($shop == "vipavenue") {
        // Тут и все размеры записаны в param каждого товара
        $sql = "
        update catalog_".$shop."
        join (select ID as min_id from catalog_".$shop.") f
        on f.min_id = catalog_".$shop.".`ID`
        set catalog_".$shop.".Group_Leader = '1'; 
        ";
    }
    else if ($shop == "test1") {
        // Тут и все размеры записаны в param каждого товара
        $sql = "
        update catalog_".$shop."
        join (select ID as min_id from catalog_".$shop.") f
        on f.min_id = catalog_".$shop.".`ID`
        set catalog_".$shop.".Group_Leader = '1'; 
        ";
    }
    else if ($shop == "test2") {
        // Тут и все размеры записаны в param каждого товара
        $sql = "
        update catalog_".$shop."
        join (select ID as min_id from catalog_".$shop.") f
        on f.min_id = catalog_".$shop.".`ID`
        set catalog_".$shop.".Group_Leader = '1'; 
        ";
    }


    $existence_result = mysqli_query($connection, $sql);
    if (!$existence_result) {
        $json_return_data = array(
            "ReturnCode" => -1, // Error
            "ReturnMessage" => ("Ошибка проверки на размеры SQL:" . $sql),
        );
        exit(json_encode($json_return_data, JSON_UNESCAPED_UNICODE));
    }


    $json_return_data = array(
        "ReturnCode" => 1,
        "ReturnMessage" => ("Success"),
    );
    exit(json_encode($json_return_data, JSON_UNESCAPED_UNICODE));
        
}


?>