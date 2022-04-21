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
$brand_like = $SEARCH_DETAILS['brand_like'];
$brand_like = str_replace(" ", "_", $brand_like);



// ------------------------------------------------
// Сперва нужно получить список таблиц которые доступны для поиска;
//        То есть все таблицы магазинов которые есть в БД
// ------------------------------------------------
$sql = "SELECT * FROM `our_partners`";
$result = mysqli_query($connection, $sql);
$partners = array();
while($row = mysqli_fetch_assoc($result)) {
    array_push($partners, strtolower($row['Partner']));
}



// ------------------------------------------------
// Проходим по каждой таблице и ищем бренды похожие по названию
// ------------------------------------------------
$suggestions = array();
foreach ($partners as $partner) {

    // Сначала нужно убедится существуют ли sql таблицы для этого магазина
    $partner_table_name = $partner;
    $partner_table_name = str_replace(" ", "_", $partner_table_name);
    $sql = "
        SELECT count(*) as cnt
        FROM information_schema.TABLES
        WHERE (TABLE_NAME = 'catalog_".$partner_table_name."')
    ";
    $table_exists = mysqli_query($connection, $sql);
    $partner_empty_yet = false;
    while($result = mysqli_fetch_assoc($table_exists)) {
        if ($result["cnt"] == 0) {
            $partner_empty_yet = true;
        }
    }
    if ($partner_empty_yet) {
        // Таблица еще не была создана, по этому пропускаем 
    } else {
        $sql = "SELECT DISTINCT `Vendor` FROM `catalog_".$partner_table_name."` WHERE `Vendor` LIKE '%".$brand_like."%'";
        $result = mysqli_query($connection, $sql);
        if (!$result) {
            exit("Can't find suggestions, SQL: ".$sql);
        }
        while($row = mysqli_fetch_assoc($result)) {
            if (!in_array($row['Vendor'], $suggestions)) {
                array_push($suggestions, $row['Vendor']);
            }
        }
    }
    
}

exit(json_encode($suggestions));

?>