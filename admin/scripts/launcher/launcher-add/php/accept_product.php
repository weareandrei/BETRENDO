<?php


header('Content-Type: text/html; charset=utf-8');

$servername = "80.78.251.198";
$username = "u1428984_admin";
$password = "Andrews8208";
$dbname = "u1428984_betrendo";

$connection = mysqli_connect($servername, $username, $password, $dbname);
mysqli_set_charset($connection, "utf8mb4");

session_start();

// ------------------------------------------------
// Получение и Декодирование JSON обьекта с данными
// ------------------------------------------------
$NEW_PRODUCT_JSON = json_decode(file_get_contents('php://input'), true);
$SIZES = $NEW_PRODUCT_JSON["sizes"];
$shop = str_replace(" ", "_", $NEW_PRODUCT_JSON['shop_name']);

// --------------------
// Выгрузка данных в БД
// --------------------

// Новый товар
if ($NEW_PRODUCT_JSON['new'] == '1') {

    // Находим последний ID в `all_products`
    $sql = "SELECT MAX(ID) FROM `all_products`";
    $result = mysqli_query($connection, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $last_id = $row['MAX(ID)'];
    }
    // Последний ID + 1 это ID товара который мы сейчас будем вставлять
    $last_id++;


    // Загружаем общие данные о товаре в `all_products`
    $sql = "
        INSERT INTO `all_products` 
        (
            Name, 
            Vendor, 
            Gender,
            Category,
            Sub_Category,
            Description,
            Collection,
            Color,
            Param,
            Main_Picture,
            Secondary_Picture,
            Picture,
            Min_Price
        )
        VALUES
        (
            '".addslashes($NEW_PRODUCT_JSON['name'])."',
            '".addslashes($NEW_PRODUCT_JSON['vendor'])."',
            '".addslashes($NEW_PRODUCT_JSON['gender'])."',
            '".addslashes($NEW_PRODUCT_JSON['category'])."',
            '".addslashes($NEW_PRODUCT_JSON['sub_category'])."',
            '".addslashes($NEW_PRODUCT_JSON['description'])."',
            '".addslashes($NEW_PRODUCT_JSON['collection'])."',
            '".addslashes($NEW_PRODUCT_JSON['color'])."',
            '".addslashes($NEW_PRODUCT_JSON['param'])."',
            '".addslashes($NEW_PRODUCT_JSON['main_picture'])."',
            '".addslashes($NEW_PRODUCT_JSON['secondary_picture'])."',
            '".addslashes($NEW_PRODUCT_JSON['picture'])."',
            '".addslashes($NEW_PRODUCT_JSON['price'])."'
        )
    ";
    $result = mysqli_query($connection, $sql);
    if (!$result) {
        exit("SQL FAIL: " . $sql);
    }


    // Загружаем конкретные данные о товаре в магазине `catalog_shop_accepted`
    $sql = "
        INSERT INTO `catalog_".$shop."_accepted`
        (
            Product_reference_ID,
            XML_Reference_ID,
            XML_Reference_Type,
            Group_Leader,
            Price,
            Oldprice,
            Size,
            Size_Unit,
            Main_Picture,
            Secondary_Picture,
            Picture,
            Url
        )
        VALUES
        (
            '".$last_id."',
            '".addslashes($NEW_PRODUCT_JSON['reference'])."',
            '".addslashes($NEW_PRODUCT_JSON['reference_type'])."',
            '1',
            '".addslashes($NEW_PRODUCT_JSON['price'])."',
            NULLIF('".addslashes($NEW_PRODUCT_JSON['old_price'])."',''),
            '".addslashes($SIZES["size"])."',
            '".addslashes($SIZES["size_unit"])."',
            '".addslashes($NEW_PRODUCT_JSON['main_picture'])."',
            '".addslashes($NEW_PRODUCT_JSON['secondary_picture'])."',
            '".addslashes($NEW_PRODUCT_JSON['picture'])."',
            '".addslashes($NEW_PRODUCT_JSON['url'])."'
        )
    ";
    $result = mysqli_query($connection, $sql);
    if (!$result) {
        exit("SQL FAIL: " . $sql);
    }


    foreach ($SIZES as $i => $size) {
        // Пропускаем первый размер потому что мы его добавили выше
        if ($i != 0) {
            $sql = "
                INSERT INTO `catalog_".$shop."_accepted`
                (
                    Product_reference_ID,
                    XML_Reference_ID,
                    XML_Reference_Type,
                    Price,
                    Oldprice,
                    Size,
                    Size_Unit,
                    Main_Picture,
                    Secondary_Picture,
                    Picture,
                    Url
                )
                VALUES
                (
                    '".$last_id."',
                    '".addslashes($size['reference'])."',
                    'standard',
                    '".addslashes($size['price'])."',
                    NULLIF('".addslashes($NEW_PRODUCT_JSON['old_price'])."',''),
                    '".addslashes($size["size"])."',
                    '".addslashes($size["size_unit"])."',
                    '',
                    '',
                    '',
                    '".addslashes($size['url'])."'
                )
            ";
            $result = mysqli_query($connection, $sql);
            if (!$result) {
                exit("SQL FAIL: " . $sql);
            }
        }
    }

    /* ------------------------------------------------
            Повышаем счетчик этого пользователя
    ------------------------------------------------ */
    $sql = 
    "UPDATE admin_users
     SET Added = Added + 1
     WHERE Username = '".$_SESSION['username']."'";

    $result = mysqli_query($connection, $sql);
    if (!$result) {
        exit("SQL FAIL: " . $sql);
    }

    exit("Success");
}

// Существующий товар
else if ($NEW_PRODUCT_JSON['new'] == '0') {

    // Обновляем минимальную цену товара если данная цена ниже установленной
    $current_min_price = "SELECT Min_Price FROM `all_products` WHERE `ID`='".$NEW_PRODUCT_JSON['id_similar']."'";
    $query_price = mysqli_query($connection, $current_min_price);
    while($price_query = mysqli_fetch_assoc($query_price)) {
        if ($price_query['Min_Price'] > $NEW_PRODUCT_JSON['price']) {
            $sql_update_min_price = "
            UPDATE `all_products`
            SET `Min_Price`='".$NEW_PRODUCT_JSON['price']."'
            WHERE `ID`='".$NEW_PRODUCT_JSON['id_similar']."';
            ";
            mysqli_query($connection, $sql_update_min_price);
        }
    }



    // Загружаем конкретные данные о товаре в магазине `catalog_shop_accepted`
    $sql = "
        INSERT INTO `catalog_".$shop."_accepted`
        (
            Product_reference_ID,
            XML_Reference_ID,
            XML_Reference_Type,
            Group_Leader,
            Price,
            Oldprice,
            Size,
            Size_Unit,
            Main_Picture,
            Secondary_Picture,
            Picture,
            Url    
        )
        VALUES
        (
            '".addslashes($NEW_PRODUCT_JSON['id_similar'])."',
            '".addslashes($NEW_PRODUCT_JSON['reference'])."',
            '".addslashes($NEW_PRODUCT_JSON['reference_type'])."',
            '1',
            '".addslashes($NEW_PRODUCT_JSON['price'])."',
            NULLIF('".addslashes($NEW_PRODUCT_JSON['old_price'])."',''),
            '".addslashes($SIZES["size"])."',
            '".addslashes($SIZES["size_unit"])."',
            '".addslashes($NEW_PRODUCT_JSON['main_picture'])."',
            '".addslashes($NEW_PRODUCT_JSON['secondary_picture'])."',
            '".addslashes($NEW_PRODUCT_JSON['picture'])."',
            '".addslashes($NEW_PRODUCT_JSON['url'])."'
        )
    ";
    $result = mysqli_query($connection, $sql);
    if (!$result) {
        exit("SQL FAIL: " . $sql);
    }


    foreach ($SIZES as $i => $size) {
        // Пропускаем первый размер потому что мы его добавили выше
        if ($i != 0) {
            $sql = "
                INSERT INTO `catalog_".$shop."_accepted` 
                (
                    Product_reference_ID,
                    XML_Reference_ID,
                    XML_Reference_Type,
                    Price,
                    Oldprice,
                    Size,
                    Size_Unit,
                    Main_Picture,
                    Secondary_Picture,
                    Picture,
                    Url
                )
                VALUES
                (
                    '".$last_id."',
                    '".addslashes($size['reference'])."',
                    'standard',
                    '".addslashes($size['price'])."',
                    NULLIF('".addslashes($NEW_PRODUCT_JSON['old_price'])."',''),
                    '".addslashes($size["size"])."',
                    '".addslashes($size["size_unit"])."',
                    '',
                    '',
                    '',
                    '".addslashes($size['url'])."'
                )
            ";
            $result = mysqli_query($connection, $sql);
            if (!$result) {
                exit("SQL FAIL: " . $sql);
            }
        }
    }


    $sql = 
    "UPDATE admin_users
     SET Appended = Appended + 1
     WHERE Username = '".$_SESSION['username']."'";

    $result = mysqli_query($connection, $sql);
    if (!$result) {
        exit("SQL FAIL: " . $sql);
    }


    exit("Success");
}


?>