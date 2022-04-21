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
$TICK_DETAILS = json_decode(file_get_contents('php://input'), true);
$reference = $TICK_DETAILS['reference'];
$shop = $TICK_DETAILS['shop'];
$shop = str_replace(" ", "_", $shop);
$missed = $TICK_DETAILS['missed'];


// ------------------------------------------------
// Отмечаем товар в БД
// ------------------------------------------------
if (!$missed) {
    $sql = "UPDATE `catalog_".$shop."` SET `Ticked`='1' WHERE `XML_Reference_ID`='".$reference."'";
    $query_result = mysqli_query($connection, $sql);
    if (!$query_result) {
        exit ("Query did not succed, SQL: " . $sql);
    } else {
        exit("Success");
    }
} else if ($missed) {
    $sql = "UPDATE `catalog_".$shop."` SET `Missed`='1' WHERE `XML_Reference_ID`='".$reference."';";
    $query_result = mysqli_query($connection, $sql);
    if (!$query_result) {
        exit ("Query did not succed, SQL: " . $sql);
    }

    $sql = "INSERT INTO `all_missed` (`XML_Reference_ID`,`XML_Reference_Type`,`Shop`) VALUES ('".$reference."', 'standard', '".$shop."');";
    $query_result = mysqli_query($connection, $sql);
    if (!$query_result) {
        exit ("Query did not succed, SQL: " . $sql);
    }
    exit("Success");
}



