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
$FAV_DETAILS = json_decode(file_get_contents('php://input'), true);
$reference = $FAV_DETAILS['reference'];
$shop = $FAV_DETAILS['shop'];
$shop = str_replace(" ", "_", $shop);
$to_do = $FAV_DETAILS['to_do'];


if ($to_do=="add") {
    $to_do = "1";
} else if ($to_do=="remove") {
    $to_do = "0";
}
    // ------------------------------------------------
// Отмечаем товар в БД
// ------------------------------------------------
$sql = "UPDATE `catalog_".$shop."` SET `Favourites`='".$to_do."' WHERE `XML_Reference_ID`='".$reference."'";
$query_result = mysqli_query($connection, $sql);
if (!$query_result) {
    exit ("Query did not succed, SQL: " . $sql);
} else {
    exit("Success");
}

?>
