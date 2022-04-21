<?php


header('Content-Type: text/html; charset=utf-8');

$servername = "80.78.251.198";
$username = "u1428984_admin";
$password = "Andrews8208";
$dbname = "u1428984_betrendo";

$connection = mysqli_connect($servername, $username, $password, $dbname);
mysqli_set_charset($connection, "utf8mb4");



// ------------------------------------------------
// Получение и Декодирование JSON обьекта с данными
// ------------------------------------------------
$SEARCH_DETAILS = json_decode(file_get_contents('php://input'), true);
$brand = $SEARCH_DETAILS['brand'];
$name = $SEARCH_DETAILS['model'];
$shop = $SEARCH_DETAILS['shop'];
$shop = str_replace(" ", "_", $shop);
$reference = $SEARCH_DETAILS['reference'];
$offset = 0;
$offset = $SEARCH_DETAILS['offset'];
$favourites_only = $SEARCH_DETAILS['favourites_only'];



// ------------------------------------------------
// Готовим JSON обьект для возврата
// ------------------------------------------------
$json_return_data = array(
    "ReturnCode" => 0,
    "ReturnMessage" => '',
    "XML_Reference_ID" => '',
    "XML_Reference_Type" => '',
    "Name" => '',
    "Vendor" => '',
    "Description" => '',
    "Price" => '',
    "Oldprice" => '',
    "Param" => '',
    "Picture" => '',
    "Url" => '',
    "Sizes" => array()
);






// ------------------------------------------------
// Дополняем наш query дополнительными параметрами
// ------------------------------------------------
$additional_query = "";
if ($favourites_only == 1) {
    $additional_query .= " AND `Favourites`='1'";
} else {
    $additional_query = "";
}

if ($shop == 'farfetch' || $shop == 'the_north_face' || $shop == 'brandshop') {
    $additional_query .= " AND `Group_Leader`='1'";
}

// ------------------------------------------------
// Находим близжайший доступный товар соответствующий запросу
// ------------------------------------------------
$results = array();
if ($reference != "") {
    $sql = "
    SELECT * 
    FROM `catalog_".$shop."` 
    WHERE `Product_Reference_ID`='$reference'
    ".$additional_query."
    ";
} else {
    $add_brand = "";
    if ($brand != "") {
        $add_brand = "AND (MATCH(`Vendor`) AGAINST('".$brand."'))";
    }
    if ($name != "") {
        $add_name = "AND (MATCH(`Name`) AGAINST('".$name."'))";
    }
    $sql = "
    SELECT * 
    FROM `catalog_".$shop."` 
    WHERE (ID != '-100' ".$add_brand." ".$add_name."
    AND `Ticked`!='1' ".$additional_query.") LIMIT 1 OFFSET ".$offset."
    ";
}






$query_result = mysqli_query($connection, $sql);
if (!$query_result) {
    $json_return_data['ReturnCode'] = -1;
    $json_return_data['ReturnMessage'] = ("Could not load data, SQL: " . $sql);
    exit(json_encode($json_return_data, JSON_UNESCAPED_UNICODE));
}
$product_info = mysqli_fetch_assoc($query_result);

// ------------------------------------------------
// Ищем размеры если магазин = Фарфетч
// ------------------------------------------------
$Group_ID = $product_info['Group_ID'];
$Url = $product_info['Url'];
if ($shop == "farfetch" || $shop == "the_north_face") {
    $additional_query_2 = "";
    if ($favourites_only == 1) {
        $additional_query_2 .= " AND `Favourites`='1'";
    } 
    if ($shop == "farfetch") {
        $sql = "SELECT * FROM `catalog_".$shop."` WHERE (`Group_ID`= '".$Group_ID."' AND `Ticked`!='1' ".$additional_query_2.")";

    } else if ($shop == "the_north_face") {
        $sql = "SELECT * FROM `catalog_".$shop."` WHERE (`Url`= '".$Url."' AND `Ticked`!='1' ".$additional_query_2.")";
    }

    $sizes_info = mysqli_query($connection, $sql);
    if (!$sizes_info) {
        $json_return_data['ReturnCode'] = -1;
        $json_return_data['ReturnMessage'] = ("Could not load sizes data, SQL: " . $sql);
        exit(json_encode($json_return_data, JSON_UNESCAPED_UNICODE));
    }
    while ($size = mysqli_fetch_assoc($sizes_info)) {
        $this_size = array(
            "XML_Reference_ID" => $size["XML_Reference_ID"],
            "Size" => $size["Size"],
            "Size_Unit" => $size["Size_Unit"],
            "Price" => $size["Price"],
            "Oldprice" => $size["Oldprice"],
            "Url" => $size["Url"]
        );
        array_push($json_return_data["Sizes"], $this_size);
    }
}



// ----------------------------------------------------------------------------
// Переводим в json формат и передаем назад в js файл
// ----------------------------------------------------------------------------
$json_return_data['ReturnCode'] = 1;
$json_return_data['ReturnMessage'] = "Data downloaded successfully";
$json_return_data['XML_Reference_ID'] = $product_info['XML_Reference_ID'];
$json_return_data['XML_Reference_Type'] = $product_info['XML_Reference_Type'];
$json_return_data['ID'] = $ID;
$json_return_data['Name'] = $product_info['Name'];
$json_return_data['Vendor'] = $product_info['Vendor'];
$json_return_data['Description'] = $product_info['Description'];
$json_return_data['Favourites'] = $product_info['Favourites'];
$json_return_data['Price'] = $product_info['Price'];
$json_return_data['Oldprice'] = $product_info['Oldprice'];
$json_return_data['Param'] = $product_info['Param'];
$json_return_data['Picture'] = $product_info['Picture'];
$json_return_data['Url'] = $product_info['Url'];
/*


Подсчет оставшегося количества элементов. Убран по причине слишком долгого выполнения у больших магазинов.




$additional_name_query = "";
if ($brand != "") {
    //$additional_vendor_query = "AND (MATCH(`Vendor`) AGAINST('".$brand."'))";
    $additional_vendor_query = "AND (`Vendor` LIKE '%$brand%')";
}
if ($name != "") {
    //$additional_name_query = "AND (MATCH(`Name`) AGAINST('".$name."'))";
    $additional_name_query = "AND (`Name` LIKE '%$name%')";
}

$sql = "SELECT COUNT(`ID`) AS cnt FROM `catalog_".$shop."` WHERE ( `Ticked`!='1' ".$additional_vendor_query." ".$additional_name_query." ".$additional_query.")";
//$json_return_data['Name'] = $sql;

//$query_result = mysqli_query($connection, $sql);
if (!$query_result) {
    $json_return_data['ReturnCode'] = -1;
    $json_return_data['ReturnMessage'] = ("Could not load data, SQL: " . $sql);
    exit(json_encode($json_return_data, JSON_UNESCAPED_UNICODE));
}
$product_info = mysqli_fetch_assoc($query_result);
$json_return_data['max_products'] = $product_info['cnt'];
*/
exit(json_encode($json_return_data, JSON_UNESCAPED_UNICODE));