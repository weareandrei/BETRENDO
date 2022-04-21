<?php


header('Content-Type: text/html; charset=utf-8');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "betrendo";

$connection = mysqli_connect($servername, $username, $password, $dbname);
mysqli_set_charset($connection, "utf8mb4");


// ------------------------------------------------
// Получение и Декодирование JSON обьекта с данными
// ------------------------------------------------
$SEARCH_DETAILS = json_decode(file_get_contents('php://input'), true);
$brand = $SEARCH_DETAILS['brand'];
$name = $SEARCH_DETAILS['model'];
$count = $SEARCH_DETAILS['count'];

if ($count == 1) {
    $add_limit = "";
} else {
    $add_limit = " LIMIT 1";
}

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
// Проходим по каждой таблице и ищем подходящие результаты
// ------------------------------------------------
$results = array();
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
        if ($partner_table_name == 'farfetch' || $partner_table_name == 'the_north_face' || $partner_table_name == 'brandshop') {
            $additional_query = "AND `Group_Leader`='1' ";
        } else {
            $additional_query = "";
        }

        $additional_name_query = "";
        if ($name != "") {
            $additional_name_query = "AND (MATCH(`Name`) AGAINST('".$name."'))";      
        }

        $sql = "SELECT COUNT(`ID`) AS cnt FROM (SELECT * FROM `catalog_".$partner_table_name."` WHERE ((MATCH(`Vendor`) AGAINST('".$brand."')) ".$additional_name_query." AND `Ticked`!='1' ".$additional_query.") $add_limit) as t";
        $result = mysqli_query($connection, $sql);
        if (!$result) {
            exit("Can't Load data for Partners, SQL:".$sql);
        }
        while($row = mysqli_fetch_assoc($result)) {
            $result_dict = array();
            array_push($result_dict, $row['cnt']);
            $sql = "SELECT COUNT(`ID`) AS cnt FROM (SELECT * FROM `catalog_".$partner_table_name."` WHERE ((MATCH(`Vendor`) AGAINST('".$brand."')) ".$additional_name_query." AND `Ticked`!='1' ".$additional_query." AND `Favourites`='1') $add_limit) as t";
            $result = mysqli_query($connection, $sql);
            while($row = mysqli_fetch_assoc($result)) {
                array_push($result_dict, $row['cnt']);
            }
            $results[$partner] = $result_dict;
        }
    }
}

exit(json_encode($results));
?>